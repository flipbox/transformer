<?php

/**
 * @copyright  Copyright (c) Flipbox Digital Limited
 * @license    https://flipboxfactory.com/software/transformer/license
 * @link       https://www.flipboxfactory.com/software/transformer/
 */

namespace flipbox\transformer\transformers\field;

use craft\base\ElementInterface;
use craft\base\Field;
use craft\base\FieldInterface;

/**
 * @author Flipbox Factory <hello@flipboxfactory.com>
 * @since 1.0.0
 */
trait FieldTrait
{
    /**
     * @var FieldInterface|Field
     */
    protected $field;

    /**
     * @inheritdoc
     */
    public function fieldData($data)
    {
        if ($data instanceof ElementInterface) {
            $data = $data->getFieldValue(
                $this->field->handle
            );
        }
        return $data;
    }
}
