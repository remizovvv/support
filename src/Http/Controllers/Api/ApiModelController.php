<?php

namespace Omadonex\Support\Http\Controllers\Api;

use Illuminate\Http\Request;
use Omadonex\Support\Classes\CustomConstants;
use Omadonex\Support\Classes\Exceptions\OmxBadParameterActiveException;
use Omadonex\Support\Classes\Exceptions\OmxBadParameterPaginateException;
use Omadonex\Support\Classes\Exceptions\OmxBadParameterRelationsException;
use Omadonex\Support\Classes\Exceptions\OmxBadParameterTrashedException;
use Omadonex\Support\Interfaces\Model\IModelRepository;
use Omadonex\Support\Interfaces\Model\IModelService;

class ApiModelController extends ApiBaseController
{
    protected $repo;
    protected $service;

    protected $trashed;
    protected $relations;
    protected $active;
    protected $paginate;

    public function __construct(IModelRepository $repo, IModelService $service, Request $request)
    {
        parent::__construct($request);
        $this->repo = $repo;
        $this->service = $service;
        if ($request->isMethod('get')) {
            $this->trashed = $this->getParamTrashed($request);
            $this->relations = $this->getParamRelations($request, $this->repo->getAvailableRelations());
            $this->active = $this->getParamActive($request);
            $this->paginate = $this->getParamPaginate($request);
        }
    }

    private function getParamRelations(Request $request, $availableRelations)
    {
        $data = $request->all();
        if (!array_key_exists('relations', $data) || ($data['relations'] === 'true')) {
            return true;
        }

        if ($data['relations'] === 'false') {
            return false;
        }

        if (is_array($data['relations']) && empty(array_diff($data['relations'], $availableRelations))) {
            return $data['relations'];
        }

        throw new OmxBadParameterRelationsException($availableRelations);
    }

    private function getParamActive(Request $request)
    {
        $data = $request->all();
        if (!array_key_exists('active', $data)) {
            return null;
        }

        if ($data['active'] === 'true') {
            return true;
        }

        if ($data['active'] === 'false') {
            return false;
        }

        throw new OmxBadParameterActiveException;
    }

    private function getParamPaginate(Request $request)
    {
        $data = $request->all();
        if (!array_key_exists('paginate', $data) || ($data['paginate'] === 'true')) {
            return true;
        }

        if ($data['paginate'] === 'false') {
            return false;
        }

        if (is_numeric($data['paginate'])) {
            return $data['paginate'];
        }

        throw new OmxBadParameterPaginateException;
    }

    private function getParamTrashed(Request $request)
    {
        $data = $request->all();
        if (!array_key_exists('trashed', $data)) {
            return null;
        }

        if (in_array($data['trashed'], [CustomConstants::DB_QUERY_TRASHED_WITH, CustomConstants::DB_QUERY_TRASHED_ONLY])) {
            return $data['trashed'];
        }

        throw new OmxBadParameterTrashedException;
    }

    protected function modelFind($id, $resource = false, $smart = false, $smartField = null)
    {
        return $this->repo->find($id, $resource, $this->relations, $this->trashed, $smart, $smartField);
    }

    protected function modelList($resource = false)
    {
        return $this->repo->list($resource, $this->relations, $this->trashed, $this->active, $this->paginate);
    }

    protected function modelCreate($data, $resource = false)
    {
        $model = $this->service->create($data);
        if ($resource) {
            return $this->repo->getResource($model);
        }

        return $model;
    }

    protected function modelUpdate($id, $data, $resource = false)
    {
        $model = $this->service->update($id, $data);
        if ($resource) {
            return $this->repo->getResource($model);
        }

        return $model;
    }
}