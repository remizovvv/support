<?php
/**
 * Created by PhpStorm.
 * User: omadonex
 * Date: 12.02.2018
 * Time: 13:16
 */

namespace Omadonex\Support\Classes\Exceptions;

class OmxBadParameterRelationsException extends \Exception
{
    protected $availableRelations;

    public function __construct($availableRelations)
    {
        $this->availableRelations = $availableRelations;
        $relationsStr = implode(", ", $availableRelations);
        $message = "Параметр `relations` может принимать одно из следующих значений: " .
            "false | true | array ($relationsStr)";
        parent::__construct($message);
    }
}