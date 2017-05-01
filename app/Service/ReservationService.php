<?php
namespace App\Service;

use DB, Log;
use App\Exception\PreconditionException;
use App\Book;
use App\Reservation;
use App\BookUser;

class ReservationService
{
    public function readAllByUser(BookUser $user)
    {
        return Reservation::where('user_id', $user->id)
            ->orderBy('created_at')
            ->orderBy('id')
            ->get();
    }

    public function read($reservationCode)
    {
        return Reservation::where('reservation_code', $reservationCode)->first();
    }

    public function book(BookUser $user, array $inputs)
    {
        return $this->store(new Reservation(), $user, $inputs);
    }

    public function update(Reservation $reservation, BookUser $user, array $inputs)
    {
        if ($user->id !== $reservation->user_id) {
            throw new PreconditionException('could_not_update');
        }

        DB::transaction(function () use ($user, $reservation, $inputs) {
            Log::debug('book : ' . var_export($reservation->book, true));
            $reservation->book->incrementInventory($reservation->quantity);
            $this->store($reservation, $user, $inputs);
        });
    }

    public function cancel(Reservation $reservation, BookUser $user)
    {
        if ($user->id !== $reservation->user_id) {
            throw new PreconditionException('could_not_cancel');
        }

        DB::transaction(function () use ($user, $reservation) {
            $reservation->book->incrementInventory($reservation->quantity);
            $reservation->delete();
        });
    }

    protected function store(Reservation $reservation, BookUser $user, array $inputs)
    {
        /** @var Book $book */
        $book = Book::where('asin', $inputs['asin'])->first();
        if (empty($book)) {
            throw new PreconditionException('book_not_found');
        }
        if ($book->inventory < $inputs['quantity']) {
            throw new PreconditionException('not_enough_book_inventory');
        }

        DB::transaction(function () use ($user, $book, &$reservation, $inputs) {
            $affectedRows = $book->decrementInventory($inputs['quantity']);
            if ($affectedRows !== 1) {
                throw new PreconditionException('not_enough_book_inventory');
            }

            $reservation->user_id = $user->id;
            $reservation->book_id = $book->id;
            $reservation->quantity = $inputs['quantity'];
            if (empty($reservation->reservation_code)) {
                $reservation->reservation_code = $reservation->generateReservationCode();
            }
            $reservation->save();
        });

        return $reservation;
    }
}