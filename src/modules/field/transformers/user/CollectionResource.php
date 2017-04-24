<?php

/**
 * @copyright  Copyright (c) Flipbox Digital Limited
 * @license    https://flipboxfactory.com/software/transformer/license
 * @link       https://www.flipboxfactory.com/software/transformer/
 */

namespace flipbox\transformer\modules\field\transformers\user;

use craft\elements\db\UserQuery;
use craft\fields\Users as UsersField;
use flipbox\transformer\modules\element\transformers\user\CollectionResource as UserCollectionResource;
use flipbox\transformer\modules\field\transformers\FieldTrait;

/**
 * @author Flipbox Factory <hello@flipboxfactory.com>
 * @since 1.0.0
 *
 * @property UsersField $field
 * @property UserQuery $data
 */
class CollectionResource extends UserCollectionResource
{

    use FieldTrait;

    /**
     * @param UsersField $field
     * @param array $config
     */
    public function __construct(UsersField $field, array $config = [])
    {
        $this->field = $field;
        parent::__construct($config);
    }

    /**
     * @inheritdoc
     */
    public function setData($data)
    {
        $this->data = $this->prepareData($data);
        return $this;
    }

    /**
     * @param $data
     * @return UserQuery
     */
    protected function prepareData($data): UserQuery
    {
        return $this->fieldData($data);
    }

    /**
     * @return array
     */
    protected function queryParams(): array
    {
        return array_merge(
            parent::queryParams(),
            ['id']
        );
    }

}
