<?php

namespace flipbox\transformer\modules\field\transformers\entry;

use craft\base\FieldInterface;
use craft\elements\db\UserQuery;
use craft\elements\User;
use flipbox\transform\resources\Item;
use flipbox\transform\resources\ResourceInterface;
use flipbox\transform\Scope;
use flipbox\transformer\modules\element\transformers\user\CollectionResource as ElementUserCollection;
use flipbox\transformer\modules\field\transformers\FieldTrait;

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
