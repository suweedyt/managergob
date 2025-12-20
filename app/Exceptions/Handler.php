<?php

namespace App\Exceptions;

use Throwable;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;

class Handler extends ExceptionHandler
{
    /**
     * Render an exception into an HTTP response.
     */
    public function render($request, Throwable $e)
    {
        // En desarrollo deja el comportamiento por defecto (ver excepciones)
        if (config('app.debug')) {
            return parent::render($request, $e);
        }

        // Loguear/registrar la excepción (report ya lo hace normalmente)
        $this->report($e);

        // Si la petición espera JSON (API), devolver JSON genérico
        if ($request->expectsJson()) {
            $status = ($e instanceof HttpExceptionInterface) ? $e->getStatusCode() : 500;
            $message = $status === 500 ? 'Error interno del servidor' : ($e->getMessage() ?: 'Error');
            return response()->json(['message' => $message], $status);
        }

        // Si es HttpException usar el status y la vista errors.{status} si existe
        if ($e instanceof HttpExceptionInterface) {
            $status = $e->getStatusCode();
            if (view()->exists("errors.{$status}")) {
                return response()->view("errors.{$status}", [], $status);
            }
        }

        // Vista fallback: errors.default o errors.500
        if (view()->exists('errors.default')) {
            return response()->view('errors.default', [], 500);
        }

        // Por defecto en producción, mostrar vista 500 genérica (pasar exception solo para logs)
        return response()->view('errors.500', [], 500);
    }
}