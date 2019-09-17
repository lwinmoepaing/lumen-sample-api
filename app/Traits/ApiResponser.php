<?php

namespace App\Traits;

use Illuminate\Http\Response;

/**
 * 
 */
trait ApiResponser
{

    public function successResponse($data, array $extraData = null, $code = Response::HTTP_OK)
    {
        $response = ['data' => $data];
        if ($extraData) {
            $response = array_merge($response, $extraData);
        }

        return response()->json($response, $code);
    }

    public function errorResponse($error, $code)
    {
        return response()->json(['error' => $error, 'code' => $code], $code);
    }
}
