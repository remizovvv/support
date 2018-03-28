<?php
/**
 * Created by PhpStorm.
 * User: omadonex
 * Date: 12.02.2018
 * Time: 13:16
 */

namespace Omadonex\Support\Classes\Exceptions;

class OmxBadParameterActiveException extends \Exception
{
    public function __construct()
    {
        parent::__construct("Параметр `active` может принимать одно из следующих значений: false | true");
    }
}