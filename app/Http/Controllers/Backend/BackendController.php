<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;

class BackendController extends Controller
{
    const DS = DIRECTORY_SEPARATOR;

    public function __construct()
    {
        $this->middleware('admin');
    }

    protected function getModuleUrl()
    {
        return \Request::segment(2) . '/' . \Request::segment(3);
    }

}
