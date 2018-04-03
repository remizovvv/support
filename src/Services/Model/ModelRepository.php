<?php
/**
 * Created by PhpStorm.
 * User: omadonex
 * Date: 06.02.2018
 * Time: 21:34
 */

namespace Omadonex\Support\Services\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Omadonex\Support\Classes\CustomConstants;
use Omadonex\Support\Classes\Exceptions\OmxModelNotFoundException;
use Omadonex\Support\Classes\Exceptions\OmxModelNotSmartFoundException;
use Omadonex\Support\Classes\Exceptions\OmxModelNotUsesTraitException;
use Omadonex\Support\Interfaces\Model\IModelRepository;
use Omadonex\Support\Traits\CanBeActivatedTrait;
use Omadonex\Support\Transformers\PaginateResourceCollection;

abstract class ModelRepository implements IModelRepository
{
    protected $model;
    protected $modelClass;
    protected $resourceClass;

    public function __construct(Model $model, $resourceClass)
    {
        $this->model = $model;
        $this->modelClass = get_class($model);
        $this->resourceClass = $resourceClass;
    }

    protected function attachRelations($qb, $relations)
    {
        $prop = 'availableRelations';
        if (($relations === true)
            && property_exists($this->modelClass, $prop)
            && is_array($this->model->$prop)) {
            $qb->with($this->model->$prop);
        }

        if (is_array($relations)) {
            $qb->with($relations);
        }

        return $qb;
    }

    protected function getPaginatedResult($qb, $paginate)
    {
        return (!$paginate) ? $qb->get() : $qb->paginate(($paginate === true) ? $this->model->getPerPage() : $paginate);
    }

    protected function makeQB($relations, $trashed, $active)
    {
        $qb = $this->model->query();

        if (!is_null($trashed)) {
            if (!in_array(SoftDeletes::class, class_uses($this->modelClass))) {
                throw new OmxModelNotUsesTraitException($this->modelClass, SoftDeletes::class);
            }

            if ($trashed === CustomConstants::DB_QUERY_TRASHED_WITH) {
                $qb->withTrashed();
            }

            if ($trashed === CustomConstants::DB_QUERY_TRASHED_ONLY) {
                $qb->onlyTrashed();
            }
        }

        if (!is_null($active)) {
            if (!in_array(CanBeActivatedTrait::class, class_uses($this->modelClass))) {
                throw new OmxModelNotUsesTraitException($this->modelClass, CanBeActivatedTrait::class);
            }
            $qb->byActive($active);
        }

        return $this->attachRelations($qb, $relations);
    }

    public function getModel()
    {
        return $this->model;
    }

    public function query()
    {
        return $this->model->query();
    }

    public function getAvailableRelations()
    {
        return $this->model->availableRelations ?: [];
    }

    public function toResourceIfNeed($resource, $objData, $paginate = true)
    {
        if (!$resource) {
            return $objData;
        }

        if ($objData instanceof Model) {
            return new $this->resourceClass($objData);
        }

        if ($paginate) {
            return new PaginateResourceCollection($objData, $this->resourceClass);
        }

        return $this->resourceClass::collection($objData);
    }

    public function getListedResult($qb, $resource, $paginate)
    {
        $result = $this->getPaginatedResult($qb, $paginate);

        return $this->toResourceIfNeed($resource, $result, $paginate);
    }

    public function find($id, $resource = false, $relations = true, $trashed = null, $smart = false, $smartField = null)
    {
        if (!$smart) {
            $model = $this->makeQB($relations, $trashed, null)->find($id);
            if (is_null($model)) {
                throw new OmxModelNotFoundException($this->model, $id);
            }
        } else {
            $field = $smartField ?: $this->model->getRouteKeyName();
            $model = $this->makeQB($relations, $trashed, null)->where($field, $id)->first();
            if (is_null($model)) {
                throw new OmxModelNotSmartFoundException($this->model, $id, $field);
            }
        }

        return $this->toResourceIfNeed($resource, $model);
    }

    public function list($resource = false, $relations = true, $trashed = null, $active = null, $paginate = true, $conditionsCallback = null)
    {
        $qb = $this->makeQB($relations, $trashed, $active);

        if (is_callable($conditionsCallback)) {
            $qb = $conditionsCallback($qb);
        }

        return $this->getListedResult($qb, $resource, $paginate);
    }

    public function agrCount($trashed = null, $active = null)
    {
        return $this->makeQB(false, $trashed, $active)->count();
    }
}