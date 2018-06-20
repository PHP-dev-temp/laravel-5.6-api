<?php

namespace App\Exceptions;

use App\Traits\ApiResponse;
use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Exception\MethodNotAllowedException;

class Handler extends ExceptionHandler
{
    use ApiResponse;

    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    /**
     * Report or log an exception.
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
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $exception)
    {
        if($exception instanceof ValidationException){
            $errors = $exception->validator->errors()->getMessages();
            return $this->errorResponse($errors, 422);
        }

        if($exception instanceof ModelNotFoundException){
            $errors = strtolower(class_basename($exception->getModel())) . ' not found!';
            return $this->errorResponse($errors, 404);
        }

        if($exception instanceof AuthenticationException){
            $errors = 'Unauthenticated!';
            return $this->errorResponse($errors, 401);
        }

        if($exception instanceof AuthorizationException){
            $errors = $exception->getMessage();
            return $this->errorResponse($errors, 403);
        }

        if($exception instanceof NotFoundHttpException){
            $errors = 'The specified URL can not be found!';
            return $this->errorResponse($errors, 404);
        }

        if($exception instanceof MethodNotAllowedHttpException){
            $errors = 'The specified method is not allowed!';
            return $this->errorResponse($errors, 405);
        }

        if($exception instanceof HttpException){
            $errors = $exception->getMessage();
            return $this->errorResponse($errors, $exception->getStatusCode());
        }

        //return parent::render($request, $exception);
        return $this->errorResponse('Unexpected exception!', 500);
    }
}
