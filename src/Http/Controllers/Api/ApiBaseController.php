<?php

namespace Omadonex\Support\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\Resource;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Routing\Controller;
use Omadonex\Support\Classes\Utils\UtilsResponseJson;
use App\User;

class ApiBaseController extends Controller
{
    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    protected function okResponse($data = [], $wrap = false)
    {
        $finalData = $data;
        if (($data instanceof Resource) || ($data instanceof ResourceCollection)) {
            $finalData = $data->toResponse($this->request)->getData();
        }
        return UtilsResponseJson::okResponse($finalData, $wrap);
    }

    protected function errorResponse($errorMsg = '', $wrap = false)
    {
        return UtilsResponseJson::errorResponse($errorMsg, $wrap);
    }

    protected function getAuthUser()
    {
        $authInfo = $this->request->header('Authorization');
        $token = explode(' ', $authInfo)[1];
        return User::where('api_token', $token)->first();
    }
}