<?php

/**
 * @copyright  Copyright (c) Flipbox Digital Limited
 * @license    https://flipboxfactory.com/software/transformer/license
 * @link       https://www.flipboxfactory.com/software/transformer/
 */

namespace flipbox\transformer\transformers\field;

use craft\fields\data\OptionData;
use craft\fields\data\SingleOptionFieldData;
use craft\fields\RadioButtons as RadioButtonsField;
use flipbox\transform\resources\Item;
use flipbox\transform\Scope;

/**
 * @author Flipbox Factory <hello@flipboxfactory.com>
 * @since 1.0.0
 *
 * @property RadioButtonsField $field
 * @property SingleOptionFieldData $data
 */
class RadioButtons extends AbstractTransformer
{

    /**
     * @param RadioButtonsField $field
     * @param array $config
     */
    public function __construct(RadioButtonsField $field, array $config = [])
    {
        parent::__construct($field, $config);
    }

    /**
     * @inheritdoc
     */
    public function transform(Scope $scope, string $identifier = null)
    {

        $resource = new Item(
            $scope->childScope($identifier)
        );

        return $resource->transform(
            function (OptionData $data) {
                return [
                    'value' => $data->value,
                    'label' => $data->label
                ];
            },
            $this->data
        );

    }

}
