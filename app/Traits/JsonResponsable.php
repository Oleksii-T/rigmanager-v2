<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;

trait JsonResponsable
{
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
            return response()->json(['message'=>$msg,'errors' => $data], $code);
        }
        $res = [
            'success' => false,
            'data' => $data,
            'message' => $msg
        ];
        return response()->json($res, $code);
    }

    public function subscriptionErrorResponse($level)
    {
        if ($level == 1) {
            $subName = 'Premium';
        }elseif ($level == 2) {
            $subName = 'Commercial';
        } else {
            $subName = '';
        }

        $msg = "A $subName subscription required for this action.";

        activity('users')
            ->event('not-subscribed')
            ->withProperties(infoForActivityLog())
            ->log('');

        if (!request()->ajax()) {
            $msg .= ' Learn more at <a href="/plans" style="color:#ff8d11">Paid plans</a> page.';
            flash($msg, false);
            return redirect()->back();
        }

        return $this->jsonError($msg, null, 402);
    }
}
