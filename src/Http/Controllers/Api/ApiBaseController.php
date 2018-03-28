<?php

namespace Omadonex\Support\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\Resource;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Routing\Controller;
use Omadonex\Support\Classes\Utils\UtilsResponseJson;

class ApiBaseController extends Controller
{
    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    protected function okResponse($data = [])
    {
        $finalData = $data;
        if (($data instanceof Resource) || ($data instanceof ResourceCollection)) {
            $finalData = $data->toResponse($this->request)->getData();
        }
        return UtilsResponseJson::okResponse($finalData);
    }

    protected function errorResponse($errorMsg = '')
    {
        return UtilsResponseJson::errorResponse($errorMsg);
    }
}