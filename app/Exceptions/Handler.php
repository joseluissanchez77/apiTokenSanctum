<?php

namespace App\Exceptions;

use App\Exceptions\Custom\DatabaseException;
use App\Exceptions\CustomException\BadRequestException;
use App\Exceptions\CustomException\ConflictException;
use App\Exceptions\CustomException\GuzzleException;
use App\Exceptions\CustomException\NotContentException;
use App\Exceptions\CustomException\NotFoundException;
use App\Exceptions\CustomException\UnprocessableException;
use App\Traits\RestResponse;
use App\Traits\TranslateException;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

class Handler extends ExceptionHandler
{
    use RestResponse, TranslateException;

    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<Throwable>>
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register()
    {
        // $this->reportable(function (Throwable $e) {
        //     //
        // });

        $this->renderable(function (Throwable $exception, $request) {
     
            if ($exception instanceof ModelNotFoundException) {
                $model = strtolower(class_basename($exception->getModel()));
                return $this->error($request->getPathInfo(), $exception,
                    __('messages.no-exist-instance', ['model' => $this->translateNameModel($model)]), Response::HTTP_NOT_FOUND);
            }

            if ($exception instanceof NotFoundHttpException) {
                $code = $exception->getStatusCode();
                return $this->error($request->getPathInfo(), $exception, __('messages.not-found'), $code);
            }

            if ($exception instanceof AccessDeniedHttpException) {
                return $this->error($request->getPathInfo(), $exception, __('messages.forbidden'), Response::HTTP_FORBIDDEN);
            }

            if ($exception instanceof MethodNotAllowedHttpException) {
                return $this->error($request->getPathInfo(), $exception, __('messages.method-not-allowed'), Response::HTTP_METHOD_NOT_ALLOWED);
            }

            if ($exception instanceof HttpException) {
                $code = $exception->getStatusCode();
                return $this->error($request->getPathInfo(), $exception, __('messages.method-not-allowed'), $code);
            }

            if ($exception instanceof AuthenticationException) {
                return $this->error($request->getPathInfo(), $exception,
                    $exception->getMessage(), Response::HTTP_UNAUTHORIZED);
            }

            if ($exception instanceof AuthorizationException) {
                return $this->error($request->getPathInfo(), $exception,
                    __('messages.forbidden'), Response::HTTP_FORBIDDEN);
            }

            if ($exception instanceof ValidationException) {
                $errors = $exception->validator->errors()->all();

                return $this->error($request->getPathInfo(), $exception,
                    $errors, Response::HTTP_BAD_REQUEST);
            }
            if ($exception instanceof DatabaseException) {
                if (config('app.debug')) {
                    return $this->error($request->getPathInfo(), $exception, $exception->getMessage(), $exception->getStatusCode());
                }
                return $this->error($request->getPathInfo(), $exception, __('messages.error-database'), $exception->getStatusCode());
            }

            if ($exception instanceof UnprocessableException
                || $exception instanceof ConflictException
                || $exception instanceof BadRequestException
                || $exception instanceof NotContentException
                || $exception instanceof NotFoundException
                || $exception instanceof GuzzleException
                ) {

                $code = $exception->getStatusCode();
                $message = $exception->getMessage();
                return $this->error($request->getPathInfo(), $exception, $message, $code);
            }

            if (config('app.debug')) {
                //return parent::render($request, $exception);
                return $this->error($request->getPathInfo(), $exception, $exception->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
            }

            return $this->error($request->getPathInfo(), $exception, __('messages.internal-server-error'), Response::HTTP_INTERNAL_SERVER_ERROR);
        });
    }
}
