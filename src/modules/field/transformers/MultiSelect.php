<?php

/**
 * @copyright  Copyright (c) Flipbox Digital Limited
 * @license    https://flipboxfactory.com/software/transformer/license
 * @link       https://www.flipboxfactory.com/software/transformer/
 */

namespace flipbox\transformer\modules\field\transformers;

use craft\fields\data\MultiOptionsFieldData;
use craft\fields\data\OptionData;
use craft\fields\MultiSelect as MultiSelectField;
use flipbox\transform\resources\Collection;
use flipbox\transform\Scope;

/**
 * @author Flipbox Factory <hello@flipboxfactory.com>
 * @since 1.0.0
 *
 * @property MultiSelectField $field
 * @property MultiOptionsFieldData $data
 */
class MultiSelect extends AbstractTransformer
{

    /**
     * @param MultiSelectField $field
     * @param array $config
     */
    public function __construct(MultiSelectField $field, array $config = [])
    {
        parent::__construct($field, $config);
    }

    /**
     * @inheritdoc
     */
    public function transform(Scope $scope, string $identifier = null)
    {

        $resource = new Collection(
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
