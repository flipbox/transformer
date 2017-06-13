<?php

/**
 * @copyright  Copyright (c) Flipbox Digital Limited
 * @license    https://flipboxfactory.com/software/transformer/license
 * @link       https://www.flipboxfactory.com/software/transformer/
 */

namespace flipbox\transformer\transformers\field;

use craft\fields\data\MultiOptionsFieldData;
use craft\fields\Table as TableField;
use Flipbox\Transform\Resources\Collection;
use Flipbox\Transform\Scope;

/**
 * @author Flipbox Factory <hello@flipboxfactory.com>
 * @since 1.0.0
 *
 * @property TableField $field
 * @property MultiOptionsFieldData $data
 */
class Table extends AbstractTransformer
{
    /**
     * @param TableField $field
     * @param array      $config
     */
    public function __construct(TableField $field, array $config = [])
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

        /**
        * @var TableField $field
        */
        $columns = $this->field->columns;

        return $resource->transform(
            function ($data) use ($columns) {
                $value = [];
                foreach ($columns as $column => $attributes) {
                    $value[$column] = [
                        'heading' => $attributes['heading'],
                        'handle' => $attributes['handle'],
                        'value' => $data[$column] ?? null
                    ];
                }
                return $value;
            },
            $this->data
        );
    }
}
