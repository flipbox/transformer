<?php

namespace flipbox\transformer\transformers\fields;

use craft\elements\Entry;
use flipbox\transform\Scope;
use flipbox\transform\transformers\AbstractTransformer;
use flipbox\organization\elements\Organization as OrganizationElement;

class Organization extends AbstractTransformer
{

    /**
     * @param Entry $entry
     * @return array
     */
    public function transform(OrganizationElement $organization): array
    {

        return [
            'name' => $organization->title,
            'organ' => 'ization'
        ];

    }

    /**
     * @inheritdoc
     */
    public function __invoke($data, Scope $scope, string $identifier = null)
    {
        $this->scope = $scope;
        return $this->transform($data, $identifier);
    }

}