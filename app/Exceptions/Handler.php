<?php

namespace App\Exceptions;

use App\Traits\ApiResponser;
use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;
use Laravel\Lumen\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\HttpException;

class Handler extends ExceptionHandler
{
    use ApiResponser;

    /**
     * A list of the exception types that should not be reported.
     *
     * @var array
     */
    protected $dontReport = [
        AuthorizationException::class,
        HttpException::class,
        ModelNotFoundException::class,
        ValidationException::class,
    ];

    /**
     * Report or log an exception.
     *
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param  \Exception  $exception
     * @return void
     */
    public function report(Exception $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $exception
     * @return \Illuminate\Http\Response|\Illuminate\Http\JsonResponse
     */
    public function render($request, Exception $exception)
    {
        if ($exception instanceof HttpException) {
            $code = $exception->getStatusCode();
            $message = Response::$statusTexts[$code];
            return $this->errorResponse($message, $code);
        }

        if ($exception instanceof ModelNotFoundException) {
            $model = strtolower(class_basename($exception->getModel()));
            return $this->errorResponse("Does Not Exist {$model} Model .", Response::HTTP_NOT_FOUND);
        }

        if ($exception instanceof AuthorizationException) {
            return $this->errorResponse($exception->getMessage(), Response::HTTP_FORBIDDEN);
        }

        if ($exception instanceof AuthenticationException) {
            return $this->errorResponse($exception->getMessage(), Response::HTTP_UNAUTHORIZED);
        }

        if ($exception instanceof ValidationException) {
            $errors = $exception->validator->errors()->getMessages();
            return $this->errorResponse($errors, Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        if ($exception instanceof  QueryException) {
            $errors = "Some Query Errors Or Connectons";
            return $this->errorResponse($errors, Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        if (env('APP_DEBUG', false)) {
            return parent::render($request, $exception);
        }

        $this->errorResponse('Unexpected Error Try Again Later.', Response::HTTP_INTERNAL_SERVER_ERROR);
    }
}
