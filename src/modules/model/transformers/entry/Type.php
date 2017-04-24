<?php

namespace flipbox\transformer\modules\model\transformers\entry;

use craft\models\EntryType;
use flipbox\transform\transformers\AbstractTransformer;

/**
 * @package flipbox\transformer\modules\field\transformers\entry
 *
 * @property EntryType $model
 */
class Type extends AbstractTransformer
{

    /**
     * @return string
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