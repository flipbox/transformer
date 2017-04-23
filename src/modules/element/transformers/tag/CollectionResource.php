<?php

namespace flipbox\transformer\modules\element\transformers\tag;

use flipbox\transform\resources\Collection;
use flipbox\transform\resources\ResourceInterface;
use flipbox\transform\Scope;

class CollectionResource extends ItemResource
{

    /**
     * @param Scope $scope
     * @return ResourceInterface
     */
    protected function createResource(Scope $scope): ResourceInterface
    {
        return new Collection($scope);
    }

}