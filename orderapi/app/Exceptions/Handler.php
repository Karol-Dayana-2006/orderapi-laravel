<?php

namespace App\Exceptions;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Exception\MethodNotAllowedException;
use Symfony\Component\Routing\Exception\RouteNotFoundException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    private $url = [
        'causal',
        'activity',
        'Observation',
        'technician',
        'order',
        'type_activity',
        'user'
    ];

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            //
        });

        $this->renderable(function (NotFoundHttpException $e, $request) {
            //añadir el prefijo api/ a la vista de urls
            $urlFinal = preg_filter('/^/', 'api/', $this->url);
            //añadir el sufijo / a la lista de urls
            $urlFinal = preg_filter('/$/', '/*', $urlFinal);

            if($request->is($urlFinal))
            {
                return Response()->json([
                    'message' => 'Registro no encontrado'
                ], Response::HTTP_NOT_FOUND);
            }
        });

        $this->renderable(function (MethodNotAllowedException $e, $request){
            return Response()->json([
                'message' => 'Metodo no encontrado o sorportado'
            ], Response::HTTP_METHOD_NOT_ALLOWED);
        });
    }

    public function render($request, Throwable $exception)
    {
        if($exception instanceof AuthorizationException)
        {
            return Response()->json([
                'message' => 'Acceso prohibido al recurso'
            ], Response::HTTP_FORBIDDEN);
        }

        if($exception instanceof RouteNotFoundException)
        {
            return Response()->json([
                'message' => 'Debe iniciar sección'
            ], Response::HTTP_UNAUTHORIZED);
        }

        return parent::render($request, $exception);
    }
}
