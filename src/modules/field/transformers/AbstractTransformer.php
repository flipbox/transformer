<?php

namespace flipbox\transformer\modules\field\transformers;

use craft\base\FieldInterface;
use flipbox\transform\transformers\AbstractTransformer as BaseAbstractTransformer;

/**
 * @package flipbox\transformer\modules\field\transformers\entry
 *
 * @property FieldInterface $field
 * @property string $data
 */
abstract class AbstractTransformer extends BaseAbstractTransformer implements FieldTransformerInterface
{

    use FieldTrait;

    /**
     * @param FieldInterface $field
     * @param array $config
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

}