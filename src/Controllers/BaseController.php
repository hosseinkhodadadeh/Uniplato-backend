<?php

namespace App\Controllers;

use App\Lib\Request;

abstract class BaseController
{
    /**
     * @var Request
     */
    protected Request$request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }
}