<?php

namespace App\Http\Traits;

trait RedirectRequests
{
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