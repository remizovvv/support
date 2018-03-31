<?php
/**
 * Created by PhpStorm.
 * User: omadonex
 * Date: 12.02.2018
 * Time: 13:16
 */

namespace Omadonex\Support\Classes\Exceptions;

class OmxModelNotSmartFoundException extends \Exception
{
    protected $model;
    protected $value;
    protected $field;

    public function __construct($model, $value, $field)
    {
        $this->model = $model;
        $this->value = $value;
        $this->field = $field;
        $table = $model->getTable();
        $class = get_Class($model);
        $message = "Запись в таблице `$table` с `$field`=$value не найдена (модель `$class`)";
        parent::__construct($message);
    }
}