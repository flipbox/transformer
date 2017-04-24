<?php

namespace flipbox\transformer\third\organization\element\transformers\organization;

use craft\base\ElementInterface;
use flipbox\organization\elements\Organization as OrganizationElement;
use flipbox\transformer\modules\element\transformers\AbstractTransformer;

class Organization extends AbstractTransformer
{

    /**
     * @param ElementInterface|OrganizationElement $element
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