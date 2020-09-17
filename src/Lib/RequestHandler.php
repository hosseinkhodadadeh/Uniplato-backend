<?php

namespace App\Lib;

use Exception;

class RequestHandler
{
    public function handle(Request $request): Response
    {
        $pathInfo = $request->server('PATH_INFO');
        if ($pathInfo === null) {
            return Response::createNotFound();

        }
        $parts = explode('/', $pathInfo);
        if (count($parts) !== 3) {
            return Response::createNotFound();
        }
        $className = 'App\\Controllers\\' . ucfirst($parts[1]) . 'Controller';
        if (!class_exists($className)) {
            return Response::createNotFound();
        }
        $methodName = $parts[2];
        $class = new $className($request);
        if (!method_exists($class, $methodName)) {
            return Response::createNotFound();
        }
        try {
            return $class->$methodName();
        } catch (Exception $exception) {
            return new Response(['status' => 'fail', 'type' => $exception->getMessage()], Response::HTTP_STATUS_INTERNAL);
        }
    }
}