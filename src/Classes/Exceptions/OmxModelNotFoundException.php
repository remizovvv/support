<?php
/**
 * Created by PhpStorm.
 * User: omadonex
 * Date: 12.02.2018
 * Time: 13:16
 */

namespace Omadonex\Support\Classes\Exceptions;

class OmxModelNotFoundException extends \Exception
{
    protected $model;
    protected $id;

    public function __construct($model, $id)
    {
        $this->model = $model;
        $this->id = $id;
        $table = $model->getTable();
        $class = get_Class($model);
        $message = "Запись в таблице `$table` с `id`=$id не найдена (модель `$class`)";
        parent::__construct($message);
    }
}