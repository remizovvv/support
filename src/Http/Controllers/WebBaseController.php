<?php

namespace Omadonex\Support\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class WebBaseController extends Controller
{
    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    protected function getResourceData($resourceData, $encode = true)
    {
        $data = $resourceData->toResponse($this->request)->getData();
        return $encode ? json_encode($data) : $data;
    }
}