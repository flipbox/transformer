<?php

/**
 * @copyright  Copyright (c) Flipbox Digital Limited
 * @license    https://flipboxfactory.com/software/transformer/license
 * @link       https://www.flipboxfactory.com/software/transformer/
 */

namespace flipbox\transformer\transformers\element;

use Flipbox\Transform\Resources\Collection;
use Flipbox\Transform\Resources\ResourceInterface;
use Flipbox\Transform\Scope;

/**
 * @author Flipbox Factory <hello@flipboxfactory.com>
 * @since 1.0.0
 */
abstract class AbstractCollectionResource extends AbstractItemResource
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
