<?php

namespace Omadonex\Support\Transformers;

use Illuminate\Http\Resources\Json\ResourceCollection;
use Omadonex\Support\Traits\AppendsToApiResourceTrait;

class PaginateResourceCollection extends ResourceCollection
{
    use AppendsToApiResourceTrait;

    public function __construct($resource, $resourceName)
    {
        $this->collects = $resourceName;
        parent::__construct($resource);
    }

    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request
     * @return array
     */
    public function toArray($request)
    {
        return parent::toArray($request);
    }
}
