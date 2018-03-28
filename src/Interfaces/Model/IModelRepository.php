<?php
/**
 * Created by PhpStorm.
 * User: omadonex
 * Date: 06.02.2018
 * Time: 21:34
 */

namespace Omadonex\Support\Interfaces\Model;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;
use Omadonex\Support\Classes\Exceptions\OmxModelNotFoundException;
use Omadonex\Support\Classes\Exceptions\OmxModelNotUsesTraitException;

interface IModelRepository
{
    /**
     * Возвращает используемую модель
     * @return Model
     */
    public function getModel();

    /**
     * Возвращает список доступных связей модели, либо пустой массив, если свойство отсутствует
     * @return array
     */
    public function getAvailableRelations();

    public function getResource($data);

    public function getResourceCollection($data, $paginate);

    /**
     * Находит модель по id, загружая указанные связи и учитывая `active`
     * @param $id
     * @param bool|array $relations
     * @param null|String $trashed
     * @throws OmxModelNotFoundException
     * @throws OmxModelNotUsesTraitException
     * @return Model
     */
    public function find($id, $relations = true, $trashed = null);

    public function findResource($id, $relations = true, $trashed = null);

    /**
     * Получает коллекцию элементов, загружая указанные связи и учитывая `active`
     * Возвращает пагинатор либо коллекцию, если кол-во элементов не указано, то оно будет взято из модели
     * @param bool|array $relations
     * @param null|String $trashed
     * @param bool|null $active
     * @param bool|int $paginate
     * @throws OmxModelNotUsesTraitException
     * @return LengthAwarePaginator | Collection
     */
    public function list($relations = true, $trashed = null, $active = null, $paginate = true);

    public function listResource($relations = true, $trashed = null, $active = null, $paginate = true);

    public function agrCount($trashed = null, $active = null);
}