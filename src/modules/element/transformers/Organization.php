<?php

namespace flipbox\transformer\modules\element\transformers;

use craft\base\ElementInterface;
use flipbox\organization\elements\Organization as OrganizationElement;

class Organization extends AbstractTransformer
{

    /**
     * @param ElementInterface $element
     * @return array
     */
    protected function transformElement(ElementInterface $element): array
    {

        return array_merge(
            parent::transformElement($element),
            $this->transformOrganization($element)
        );

    }

    /**
     * @param OrganizationElement $organization
     * @return array
     */
    protected function transformOrganization(OrganizationElement $organization): array
    {
        return [
            'title' => $organization->title
        ];
    }

}