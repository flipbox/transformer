<?php

/**
 * @copyright  Copyright (c) Flipbox Digital Limited
 * @license    https://flipboxfactory.com/software/transformer/license
 * @link       https://www.flipboxfactory.com/software/transformer/
 */

namespace flipbox\transformer\transformers\element\user;

use craft\base\ElementInterface;
use craft\elements\User as UserElement;
use flipbox\transformer\transformers\element\AbstractTransformer;

/**
 * @author Flipbox Factory <hello@flipboxfactory.com>
 * @since 1.0.0
 */
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
