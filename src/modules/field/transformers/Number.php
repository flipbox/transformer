<?php

/**
 * @copyright  Copyright (c) Flipbox Digital Limited
 * @license    https://flipboxfactory.com/software/transformer/license
 * @link       https://www.flipboxfactory.com/software/transformer/
 */

namespace flipbox\transformer\modules\field\transformers;

use craft\fields\Number as NumberField;

/**
 * @author Flipbox Factory <hello@flipboxfactory.com>
 * @since 1.0.0
 *
 * @property NumberField $field
 * @property string $data
 */
class Number extends AbstractTransformer
{

    /**
     * @param NumberField $field
     * @param array $config
     */
    public function __construct(NumberField $field, array $config = [])
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
