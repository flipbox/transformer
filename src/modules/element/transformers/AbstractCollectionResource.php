<?php

namespace flipbox\transformer\modules\element\transformers;

use flipbox\transform\resources\Collection as CollectionResource;
use flipbox\transform\resources\ResourceInterface;
use flipbox\transform\Scope;

abstract class AbstractCollectionResource extends AbstractItemResource
{

    /**
     * @param Scope $scope
     * @return ResourceInterface
     */
    protected function createResource(Scope $scope): ResourceInterface
    {
        return new CollectionResource($scope);
    }

}