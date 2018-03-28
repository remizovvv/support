<?php
/**
 * Created by PhpStorm.
 * User: omadonex
 * Date: 12.02.2018
 * Time: 13:16
 */

namespace Omadonex\Support\Classes\Exceptions;

class OmxBadParameterPaginateException extends \Exception
{

    public function __construct()
    {
        $message = "Параметр `paginate` может принимать одно из следующих значений: " .
            "false | true | integer";
        parent::__construct($message);
    }
}