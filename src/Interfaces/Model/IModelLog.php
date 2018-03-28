<?php
/**
 * Created by PhpStorm.
 * User: omadonex
 * Date: 06.02.2018
 * Time: 21:34
 */

namespace Omadonex\Support\Interfaces\Model;

interface IModelLog
{
    public function saveToLog($model);
}