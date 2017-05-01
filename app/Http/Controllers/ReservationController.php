<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Middleware\ApiAuthFilter;
use App\Service\ReservationService;
use Log, Validator;

class ReservationController extends Controller
{
	use ApiControllerTrait;

    protected $service;

    public function __construct(ReservationService $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        $reservations = $this->service->readAllByUser($this->getUser());
        Log::debug('reservations count:' . count($reservations));
        return $this->responseOk($reservations);
    }

    protected function getUser()
    {
        return app()[ApiAuthFilter::AUTHORIZED_USER];
    }

    public function create(Request $request)
    {
    	Log::debug(var_export($request->all(), true));
    	$validator = Validator::make($request->all(), [
            'asin' => 'required|regex:/\A[0-9a-zA-z]+\z/',
            'quantity' => 'required|numeric',
        ]);
        if ($validator->fails()) {
            return $this->responseValidationError($validator->messages());
        }

        $reservation = $this->service->book($this->getUser(), $request->all());

        return $this->responseCreated($reservation);
    }

    /**
     * @param string $reservationCode
     * @return \Illuminate\Http\JsonResponse
     */
    public function update($reservationCode)
    {
    	Log::debug(var_export(request()->all(), true));
        $validator = Validator::make(request()->all(), [
            'asin' => 'required|regex:/\A[0-9a-zA-z]+\z/',
            'quantity' => 'required|numeric',
        ]);
        if ($validator->fails()) {
            return $this->responseValidationError($validator->messages());
        }

        $reservation = $this->service->read($reservationCode);
        if (empty($reservation)) {
            return $this->responseNotFound();
        }

        $this->service->update($reservation, $this->getUser(), request()->all());
        return $this->responseOk();
    }

    /**
     * @param string $reservationCode
     * @return \Illuminate\Http\JsonResponse
     */
    public function delete($reservationCode)
    {
        $reservation = $this->service->read($reservationCode);
        if (empty($reservation)) {
            return $this->responseNotFound();
        }

        $this->service->cancel($reservation, $this->getUser());
        return $this->responseOk();
    }
}
