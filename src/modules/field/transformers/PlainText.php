<?php

namespace flipbox\transformer\modules\field\transformers;

use craft\fields\PlainText as PlainTextField;

/**
 * @package flipbox\transformer\modules\field\transformers\entry
 *
 * @property PlainTextField $field
 * @property string $data
 */
class PlainText extends AbstractTransformer
{

    /**
     * @param PlainTextField $field
     * @param array $config
     */
    public function __construct(PlainTextField $field, array $config = [])
    {
        parent::__construct($field, $config);
    }

    /**
     * @return string
     */
    public function transform()
    {
        return (string)$this->data;
    }

}