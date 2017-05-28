<?php

namespace App\Http\Traits;

trait RedirectRequests
{
    /**
     * Return with error message
     *
     * @param $message
     * @param $routeName
     * @param array $routeParams
     * @return \Illuminate\Http\RedirectResponse
     */
    protected function sendErrorResponse($message, $routeName, $routeParams = array())
    {
        return redirect()
            ->route($routeName, $routeParams)
            ->with('error', $message);
    }

    /**
     * Return with info message
     *
     * @param $message
     * @param $routeName
     * @param array $routeParams
     * @return \Illuminate\Http\RedirectResponse
     */
    protected function sendInfoResponse($message, $routeName, $routeParams = array())
    {
        return redirect()
            ->route($routeName, $routeParams)
            ->with('info', $message);
    }

    /**
     * Return with success message
     *
     * @param $message
     * @param $routeName
     * @param array $routeParams
     * @return \Illuminate\Http\RedirectResponse
     */
    protected function sendSuccessResponse($message, $routeName, $routeParams = array())
    {
        return redirect()
            ->route($routeName, $routeParams)
            ->with('success', $message);
    }
}