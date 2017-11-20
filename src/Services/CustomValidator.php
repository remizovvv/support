<?php

namespace Omadonex\Support\Services;

class CustomValidator
{
    public function timeValidate($attribute, $value, $parameters, $validator)
    {
        return preg_match("/(2[0-3]|[01][0-9]):([0-5][0-9])/", $value);
    }

    public function phoneValidate($attribute, $value, $parameters, $validator)
    {
        return preg_match("/\+7\d{10}$/", $value);
    }
}