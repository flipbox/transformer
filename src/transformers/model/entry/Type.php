<?php

/**
 * @copyright  Copyright (c) Flipbox Digital Limited
 * @license    https://flipboxfactory.com/software/transformer/license
 * @link       https://www.flipboxfactory.com/software/transformer/
 */

namespace flipbox\transformer\transformers\model\entry;

use craft\models\EntryType;
use flipbox\transform\transformers\AbstractTransformer;

/**
 * @author Flipbox Factory <hello@flipboxfactory.com>
 * @since 1.0.0
 *
 * @property EntryType $model
 */
class Type extends AbstractTransformer
{

    /**
     * @param EntryType $model
     * @return array
     */
    public function transform(EntryType $model)
    {
        return [
            'id' => (int)$model->id,
            'name' => $model->name,
            'handle' => $model->handle
        ];
    }

}