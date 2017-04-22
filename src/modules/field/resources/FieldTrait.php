<?php

namespace flipbox\transformer\modules\field\resources;

use craft\base\ElementInterface;
use craft\base\Field;
use craft\base\FieldInterface;

trait FieldTrait
{

    /**
     * @var FieldInterface|Field
     */
    protected $field;

    /**
     * @inheritdoc
     */
    public function prepareData($data)
    {

        if ($data instanceof ElementInterface) {

            $data = $data->getFieldValue(
                $this->field->handle
            );

        }

        return $data;

    }

}