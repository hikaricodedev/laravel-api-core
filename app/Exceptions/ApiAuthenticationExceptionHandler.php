<?php

namespace App\Exceptions;

use Illuminate\Auth\AuthenticationException;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Routing\ResponseFactory;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class ApiAuthenticationExceptionHandler
{
    /**
     * Render the given exception.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Throwable  $e
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function render($request, Throwable $e): Response|Application|ResponseFactory
    {
        if ($request->expectsJson() && $e instanceof AuthenticationException) {
            return response()->json(['message' => 'Unauthenticated.'], Response::HTTP_UNAUTHORIZED);
        }

        // Jika bukan permintaan JSON atau bukan AuthenticationException,
        // biarkan Laravel menangani secara default.
        throw $e;
    }
}
