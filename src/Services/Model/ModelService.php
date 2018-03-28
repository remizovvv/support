<?php
/**
 * Created by PhpStorm.
 * User: omadonex
 * Date: 06.02.2018
 * Time: 21:34
 */

namespace Omadonex\Support\Services\Model;

use Omadonex\Support\Classes\Exceptions\OmxModelCanNotBeActivatedException;
use Omadonex\Support\Classes\Exceptions\OmxModelCanNotBeDeactivatedException;
use Omadonex\Support\Classes\Exceptions\OmxModelNotUsesTraitException;
use Omadonex\Support\Interfaces\Model\IModelRepository;
use Omadonex\Support\Interfaces\Model\IModelService;
use Omadonex\Support\Traits\CanBeActivatedTrait;

abstract class ModelService implements IModelService
{
    protected $repo;

    public function __construct(IModelRepository $repo)
    {
        $this->repo = $repo;
    }

    public function create($data)
    {
        return $this->repo->getModel()->create($data);
    }

    public function update($id, $data)
    {
        $model = $this->repo->find($id);
        $model->update($data);

        return $model;
    }

    public function destroy($id)
    {
        $this->repo->getModel()->destroy($id);
    }

    public function tryDestroy($id)
    {
        $this->destroy($id);
    }

    public function activate($id)
    {
        $modelClass = get_class($this->repo->getModel());
        if (!in_array(CanBeActivatedTrait::class, class_uses($modelClass))) {
            throw new OmxModelNotUsesTraitException($modelClass, CanBeActivatedTrait::class);
        }

        $model = $this->repo->find($id);
        if (!$model->canActivate()) {
             throw new OmxModelCanNotBeActivatedException($this->repo->getModel()->cantActivateText());
        }

        $model->activate();
    }

    public function deactivate($id)
    {
        $modelClass = get_class($this->repo->getModel());
        if (!in_array(CanBeActivatedTrait::class, class_uses($modelClass))) {
            throw new OmxModelNotUsesTraitException($modelClass, CanBeActivatedTrait::class);
        }

        $model = $this->repo->find($id);
        if (!$model->canDeactivate()) {
            throw new OmxModelCanNotBeDeactivatedException($this->repo->getModel()->cantDeactivateText());
        }

        $model->deactivate();
    }
}