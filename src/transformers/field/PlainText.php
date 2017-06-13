<?php

/**
 * @copyright  Copyright (c) Flipbox Digital Limited
 * @license    https://flipboxfactory.com/software/transformer/license
 * @link       https://www.flipboxfactory.com/software/transformer/
 */

namespace flipbox\transformer\transformers\field;

use craft\fields\PlainText as PlainTextField;

/**
 * @author Flipbox Factory <hello@flipboxfactory.com>
 * @since 1.0.0
 *
 * @property PlainTextField $field
 * @property string $data
 */
class PlainText extends AbstractTransformer
{
    /**
     * @param PlainTextField $field
     * @param array          $config
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
