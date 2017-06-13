<?php

/**
 * @copyright  Copyright (c) Flipbox Digital Limited
 * @license    https://flipboxfactory.com/software/transformer/license
 * @link       https://www.flipboxfactory.com/software/transformer/
 */

namespace flipbox\transformer\transformers\model;

use craft\base\FieldInterface;
use Flipbox\Transform\Scope;
use Flipbox\Transform\Transformers\AbstractTransformer as BaseAbstractTransformer;

/**
 * @author Flipbox Factory <hello@flipboxfactory.com>
 * @since 1.0.0
 *
 * @property FieldInterface $field
 * @property string $data
 */
abstract class AbstractTransformer extends BaseAbstractTransformer
{
    /**
     * @inheritdoc
     */
    public function __invoke($data, Scope $scope, string $identifier = null)
    {
        return $this->transform($scope, $identifier);
    }
}
