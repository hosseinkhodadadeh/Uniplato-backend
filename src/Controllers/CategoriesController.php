<?php

namespace App\Controllers;

use App\Lib\Response;

class CategoriesController extends BaseController
{
    public function list()
    {
        $categories = getDb()->select('SELECT id , category, counter  FROM `category`');
        return new Response(['status' => 'ok', 'categories' => $categories]);
    }

    public function save()
    {
        //Needs authorization
        getDb()->exec(
            'update category set counter = ? where id = ? ',
            [intval($this->request->post('counter')), intval($this->request->post('id'))]
        );
        return new Response(['status' => 'ok']);
    }
}