<?php

namespace flipbox\transformer\modules\field\resources;

use craft\base\FieldInterface;
use flipbox\transformer\modules\element\resources\OrganizationCollection as ElementOrganizationCollection;

class OrganizationCollection extends ElementOrganizationCollection
{

    use FieldTrait;

    /**
     * @param FieldInterface $field
     * @param array $config
     */
    public function __construct(FieldInterface $field, array $config = [])
    {
        $this->field = $field;
        parent::__construct($config);
    }

    /**
     * @inheritdoc
     */
    public function setData($data)
    {
        return parent::setData(
            $this->prepareData($data)
        );
    }

}
