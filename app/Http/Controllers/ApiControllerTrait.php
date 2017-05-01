<?php 
namespace App\Http\Controllers;

use Symfony\Component\HttpFoundation\Response as Res;
use Response;

trait ApiControllerTrait {

    public function responseOk($message = '')
    {
        return Response::json($message);
    }

    public function responseCreated($message = '')
    {
        return Response::json($message, Res::HTTP_CREATED);
    }

    public function responseNotFound()
    {
        return Response::json(null, Res::HTTP_NOT_FOUND);
    }

    public function responseValidationError($messages = [])
    {
        return $this->responseBadRequest('validation', $messages);
    }

    public function responseBadRequest($error = null, $messages = [])
    {
        $response = [
            'error' => $error,
            'messages' => $messages,
        ];
        return Response::json($response, Res::HTTP_BAD_REQUEST);
    }
}