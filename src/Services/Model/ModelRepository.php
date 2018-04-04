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

    private function getRealOptions($options)
    {
        $keysValues = [
            'resource' => false,
            'relations' => false,
            'trashed' => null,
            'smart' => false,
            'smartField' => null,
            'active' => null,
            'paginate' => false,
            'conditionsCallback' => null,
        ];

        $realOptions = [];
        foreach ($keysValues as $key => $value) {
            $realOptions[$key] = array_key_exists($key, $options) ? $options[$key] : $value;
        }

        return $realOptions;
    }

    protected function attachRelations($qb, $options)
    {
        $prop = 'availableRelations';
        if (($options['relations'] === true)
            && property_exists($this->modelClass, $prop)
            && is_array($this->model->$prop)) {
            $qb->with($this->model->$prop);
        }

        if (is_array($options['relations'])) {
            $qb->with($options['relations']);
        }

        return $qb;
    }

    protected function getPaginatedResult($qb, $paginate)
    {
        if (!$paginate) {
            return $qb->get();
        }

        return $qb->paginate(($paginate === true) ? $this->model->getPerPage() : $paginate);
    }

    protected function makeQB($options)
    {
        $qb = $this->model->query();

        if (!is_null($options['trashed'])) {
            if (!in_array(SoftDeletes::class, class_uses($this->modelClass))) {
                throw new OmxModelNotUsesTraitException($this->modelClass, SoftDeletes::class);
            }

            if ($options['trashed'] === CustomConstants::DB_QUERY_TRASHED_WITH) {
                $qb->withTrashed();
            }

            if ($options['trashed'] === CustomConstants::DB_QUERY_TRASHED_ONLY) {
                $qb->onlyTrashed();
            }
        }

        if (!is_null($options['active'])) {
            if (!in_array(CanBeActivatedTrait::class, class_uses($this->modelClass))) {
                throw new OmxModelNotUsesTraitException($this->modelClass, CanBeActivatedTrait::class);
            }
            $qb->byActive($options['active']);
        }

        if (is_callable($options['conditionsCallback'])) {
            $qb = $options['conditionsCallback']($qb);
        }

        return $this->attachRelations($qb, $options);
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

    public function toResource($modelOrCollection, $resource = false, $paginate = false)
    {
        if (!$resource) {
            return $modelOrCollection;
        }

        if ($modelOrCollection instanceof Model) {
            return new $this->resourceClass($modelOrCollection);
        }

        if ($paginate) {
            return new PaginateResourceCollection($modelOrCollection, $this->resourceClass);
        }

        return $this->resourceClass::collection($modelOrCollection);
    }

    public function find($id, $options = [])
    {
        $realOptions = $this->getRealOptions($options);

        $field = 'id';
        if ($realOptions['smart']) {
            $field = $realOptions['smartField'] ?: $this->model->getRouteKeyName();
        }
        $model = $this->makeQB($realOptions)->where($field, $id)->first();
        if (is_null($model)) {
            throw new OmxModelNotSmartFoundException($this->model, $id, $field);
        }

        return $this->toResource($model, $realOptions['resource'], false);
    }

    public function list($options = [])
    {
        $realOptions = $this->getRealOptions($options);

        $collection = $this->getPaginatedResult($this->makeQB($realOptions), $realOptions['paginate']);

        return $this->toResource($collection, $realOptions['resource'], $realOptions['paginate']);
    }

    public function agrCount($options = [])
    {
        $realOptions = $this->getRealOptions($options);

        return $this->makeQB($realOptions)->count();
    }
}