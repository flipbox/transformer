<?php

namespace flipbox\transformer\modules\field\transformers\tag;

use flipbox\transform\resources\Item;
use flipbox\transform\resources\ResourceInterface;
use flipbox\transform\Scope;

class ItemResource extends CollectionResource
{

    /**
     * @inheritdoc
     */
    public function getData(Scope $scope)
    {
        return parent::getData($scope)->one();
    }

    /**
     * @param Scope $scope
     * @return ResourceInterface
     */
    protected function createResource(Scope $scope): ResourceInterface
    {
        return new Item($scope);
    }

}
