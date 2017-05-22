<?php

/**
 * @copyright  Copyright (c) Flipbox Digital Limited
 * @license    https://flipboxfactory.com/software/transformer/license
 * @link       https://www.flipboxfactory.com/software/transformer/
 */

namespace flipbox\transformer\transformers\field\user;

use flipbox\transform\resources\Item;
use flipbox\transform\resources\ResourceInterface;
use flipbox\transform\Scope;

/**
 * @author Flipbox Factory <hello@flipboxfactory.com>
 * @since 1.0.0
 */
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
