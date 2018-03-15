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

    public static function okResponse($data, $wrap = false)
    {
        $result = $wrap ? ['data' => $data] : $data;
        return response()->json([
            'status' => true,
            'result' => $result,
        ], self::CODE_OK);
    }

    public static function errorResponse($data, $wrap = false)
    {
        $result = $wrap ? ['data' => $data] : $data;
        return response()->json([
            'status' => false,
            'result' => $result,
        ], self::CORE_ERROR);
    }
}