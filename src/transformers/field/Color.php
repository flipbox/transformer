<?php

/**
 * @copyright  Copyright (c) Flipbox Digital Limited
 * @license    https://flipboxfactory.com/software/transformer/license
 * @link       https://www.flipboxfactory.com/software/transformer/
 */

namespace flipbox\transformer\transformers\field;

use craft\fields\Color as ColorField;

/**
 * @author Flipbox Factory <hello@flipboxfactory.com>
 * @since 1.0.0
 *
 * @property string $data
 */
class Color extends AbstractTransformer
{
    /**
     * @param ColorField $field
     * @param array      $config
     */
    public function __construct(ColorField $field, array $config = [])
    {
        parent::__construct($field, $config);
    }

    /**
     * @return string
     */
    public function transform()
    {
        if (null === $this->data) {
            return null;
        }

        return (string)$this->data;
    }
}
