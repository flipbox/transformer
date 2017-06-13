<?php

/**
 * @copyright  Copyright (c) Flipbox Digital Limited
 * @license    https://flipboxfactory.com/software/transformer/license
 * @link       https://www.flipboxfactory.com/software/transformer/
 */

namespace flipbox\transformer\transformers\field;

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
abstract class AbstractTransformer extends BaseAbstractTransformer implements FieldTransformerInterface
{

    use FieldTrait;

    /**
     * @param FieldInterface $field
     * @param array          $config
     */
    public function __construct(FieldInterface $field, array $config = [])
    {
        $this->field = $field;
        parent::__construct($config);
    }

    /**
     * @inheritdoc
     */
    public function setData($data)
    {
        $this->data = $this->prepareData($data);
        return $this;
    }

    /**
     * @param $data
     * @return mixed
     */
    protected function prepareData($data)
    {
        return $this->fieldData($data);
    }

    /**
     * @inheritdoc
     */
    public function __invoke($data, Scope $scope, string $identifier = null)
    {
        return $this->transform($scope, $identifier);
    }
}
