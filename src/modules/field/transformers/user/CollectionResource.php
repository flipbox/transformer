<?php

namespace flipbox\transformer\modules\field\transformers\user;

use craft\elements\db\UserQuery;
use craft\fields\Users as UsersField;
use flipbox\transform\Scope;
use flipbox\transformer\modules\element\transformers\user\CollectionResource as ElementUserCollection;
use flipbox\transformer\modules\field\transformers\FieldTrait;

/**
 * @package flipbox\transformer\modules\field\transformers\entry
 *
 * @property UsersField $field
 * @property UserQuery $data
 */
class CollectionResource extends ElementUserCollection
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
