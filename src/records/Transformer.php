<?php

/**
 * @copyright  Copyright (c) Flipbox Digital Limited
 * @license    https://flipboxfactory.com/software/transformer/license
 * @link       https://www.flipboxfactory.com/software/transformer/
 */

namespace flipbox\transformer\records;

use craft\models\Site;
use craft\validators\SiteIdValidator;
use flipbox\spark\records\RecordWithHandle;
use yii\db\ActiveQueryInterface;

/**
 * @author Flipbox Factory <hello@flipboxfactory.com>
 * @since 1.0.0
 *
 * @property string $class
 * @property string $type
 * @property string $scope
 * @property string $context
 * @property string $config
 * @property int $siteId
 * @property Site $site
 */
class Transformer extends RecordWithHandle
{

    /**
     * The table name
     */
    const TABLE_ALIAS = 'transformers';

    /**
     * @inheritdoc
     */
    public function rules()
    {

        return array_merge(
            parent::rules(),
            [
                [
                    [
                        'class',
                        'type',
                        'scope',
                        'context'
                    ],
                    'required'
                ],
                [
                    [
                        'siteId'
                    ],
                    SiteIdValidator::class
                ]
            ]
        );

    }

    /**
     * Returns the associated site.
     *
     * @return ActiveQueryInterface The relational query object.
     */
    public function getSite(): ActiveQueryInterface
    {
        return $this->hasOne(Site::class, ['id' => 'siteId']);
    }

}