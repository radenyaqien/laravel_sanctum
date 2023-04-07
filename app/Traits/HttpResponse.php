<?php

namespace App\Traits;

trait HttpResponse
{

    protected function success($data, $message = null, $code = 200)
    {

        return response()->json(
            [
                "status" => "success",
                "msg" => $message,
                "data" => $data
            ],
            $code
        );
    }
    protected function error($data, $message = null, $code=400)
    {

        return response()->json(
            [
                "status" => "error occured",
                "msg" => $message,
                "data" => $data
            ],
            $code
        );
    }
}
