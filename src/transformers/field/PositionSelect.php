<?php

/**
 * @copyright  Copyright (c) Flipbox Digital Limited
 * @license    https://flipboxfactory.com/software/transformer/license
 * @link       https://www.flipboxfactory.com/software/transformer/
 */

namespace flipbox\transformer\transformers\field;

use craft\fields\PositionSelect as PositionSelectField;

/**
 * @author Flipbox Factory <hello@flipboxfactory.com>
 * @since 1.0.0
 *
 * @property PositionSelectField $field
 * @property string $data
 */
class PositionSelect extends AbstractTransformer
{
    /**
     * @param PositionSelectField $field
     * @param array               $config
     */
    public function __construct(PositionSelectField $field, array $config = [])
    {
        parent::__construct($field, $config);
    }

    /**
     * @inheritdoc
     */
    public function transform()
    {
        return (string)$this->data;
    }
}
