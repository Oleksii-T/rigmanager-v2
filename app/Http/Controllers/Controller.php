<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\JsonResponse;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * Format of success json response for all ajax\axios requests
     *
     */
    public function jsonSuccess($msg='', $data=null): JsonResponse
    {
        $resp = [
            'success' => true,
            'data' => $data,
            'message' => $msg
        ];
        return response()->json($resp);
    }

    /**
     * Format of error json response for all ajax\axios requests
     *
     */
    public function jsonError($msg='Server Error', $data=null, $code=500): JsonResponse
    {
        if ($code == 422) {
            return response()->json(['errors' => $errorMsg], $code);
        }
        $res = [
            'success' => false,
            'data' => $data,
            'message' => $msg
        ];
        return response()->json($res, $code);
    }
}
