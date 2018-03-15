<?php
/**
 * Created by PhpStorm.
 * User: omadonex
 * Date: 12.02.2018
 * Time: 15:42
 */

namespace Omadonex\Support\Classes\Utils;

class UtilsResponseJson
{

    const CODE_OK = 200;
    const CORE_ERROR = 422;

    public static function okResponse($data)
    {
        return response()->json([
            'status' => true,
            'result' => $data,
        ], self::CODE_OK);
    }

    public static function errorResponse($errorMsg)
    {
        return response()->json([
            'status' => false,
            'result' => $errorMsg,
        ], self::CORE_ERROR);
    }
}