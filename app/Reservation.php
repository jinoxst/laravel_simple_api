<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Ramsey\Uuid\Uuid;

class Reservation extends Model
{
    public function user()
    {
        return $this->BelongsTo('App\User');
    }

    public function book()
    {
        return $this->BelongsTo('App\Book');
    }

    public function generateReservationCode()
    {
        return Uuid::uuid4()->toString();
    }
}
