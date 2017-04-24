<?php

namespace flipbox\transformer\modules\element\transformers\user;

use craft\base\ElementInterface;
use craft\elements\User as UserElement;
use flipbox\transformer\modules\element\transformers\AbstractTransformer;

class User extends AbstractTransformer
{

    /**
     * @param ElementInterface|UserElement $element
     * @return array
     */
    protected function transformElement(ElementInterface $element): array
    {

        return array_merge(
            parent::transformElement($element),
            $this->transformUser($element)
        );

    }

    /**
     * @param UserElement $user
     * @return array
     */
    protected function transformUser(UserElement $user): array
    {

        return [
            'name' => [
                'first' => $user->firstName,
                'last' => $user->lastName,
                'full' => $user->getFullName()
            ]
        ];

    }

}