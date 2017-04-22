<?php

namespace flipbox\transformer\third\organization\field\transformers\user;

use flipbox\organization\elements\db\Organization as OrganizationQuery;
use flipbox\organization\fields\User as OrganizationUserField;
use flipbox\transformer\modules\field\transformers\FieldTrait;
use flipbox\transformer\third\organization\element\transformers\organization\CollectionResource as ElementOrganizationCollection;

/**
 * @property OrganizationUserField $field
 * @property OrganizationQuery $data
 */
class CollectionResource extends ElementOrganizationCollection
{

    use FieldTrait;

    /**
     * @param OrganizationUserField $field
     * @param array $config
     */
    public function __construct(OrganizationUserField $field, array $config = [])
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
     * @return OrganizationQuery
     */
    protected function prepareData($data): OrganizationQuery
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
