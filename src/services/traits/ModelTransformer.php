<?php

/**
 * @copyright  Copyright (c) Flipbox Digital Limited
 * @license    https://flipboxfactory.com/software/transformer/license
 * @link       https://www.flipboxfactory.com/software/transformer/
 */

namespace flipbox\transformer\services\traits;

use craft\models\EntryType as EntryTypeModel;
use craft\models\Section as SectionModel;
use flipbox\transform\transformers\TransformerInterface;
use flipbox\transformer\Transformer as TransformerPlugin;
use flipbox\transformer\transformers\model\entry\Section as EntrySectionTransformer;
use flipbox\transformer\transformers\model\entry\Type as EntryTypeTransformer;
use yii\base\Model;

/**
 * @author Flipbox Factory <hello@flipboxfactory.com>
 * @since 1.0.0
 */
trait ModelTransformer
{

    /**
     * @param Model $model
     * @return TransformerInterface[]|callable[]
     */
    protected function firstPartyModel(Model $model)
    {

        $transformers = [];

        switch (get_class($model)) {

            case SectionModel::class:
                $transformers['default'] = new EntrySectionTransformer();
                break;

            case EntryTypeModel::class:
                $transformers['default'] = new EntryTypeTransformer();
                break;

            default:
                TransformerPlugin::warning(sprintf(
                    "First party transformer not found for model '%s'",
                    get_class($model)
                ));

        }

        return $transformers;

    }

}