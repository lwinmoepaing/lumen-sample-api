<?php

namespace App\Traits;


/**
 * 
 */
trait ApiResponser
{

    public function successResponse($data, array $optionArray = ["status" => 200])
    {
        $response = $this->successOptionFilter($data, $optionArray);
        return response()->json($response, $response["status"]);
    }

    public function errorResponse($error, $code)
    {
        // $response = $this->errorOptionsFilter();
        return response()->json(['error' => $error, 'code' => $code], $code);
    }

    public function successOptionFilter($data = '', array $optionArray)
    {
        // is Paginate
        $response = $this->isPaginate($optionArray) ? $data->toArray() : ['data' => $data];

        if (empty($optionArray["status"])) {
            $optionArray["status"] = 200;
        }

        if (count($optionArray) > 0) {
            $response = array_merge($response, $optionArray);
        }

        return $response;
    }

    public function isPaginate($optionArray)
    {
        return isset($optionArray["type"]) && $optionArray["type"] === "paginate";
    }
}
