<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * @param $to
     * @param $message
     * @return \Illuminate\Http\RedirectResponse
     */
    protected function sendErrorResponse($to, $message)
    {
        return redirect($to)
            ->with('error', $message);
    }

    /**
     * @param $to
     * @param $message
     * @return \Illuminate\Http\RedirectResponse
     */
    protected function sendInfoResponse($to, $message)
    {
        return redirect($to)
            ->with('info', $message);
    }
}
