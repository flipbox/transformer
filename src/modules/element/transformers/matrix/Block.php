<?php

/**
 * @copyright  Copyright (c) Flipbox Digital Limited
 * @license    https://flipboxfactory.com/software/transformer/license
 * @link       https://www.flipboxfactory.com/software/transformer/
 */

namespace flipbox\transformer\modules\element\transformers\matrix;

use craft\base\ElementInterface;
use craft\elements\MatrixBlock as MatrixBlockElement;
use flipbox\transformer\modules\element\transformers\AbstractTransformer;

/**
 * @author Flipbox Factory <hello@flipboxfactory.com>
 * @since 1.0.0
 */
class Block extends AbstractTransformer
{

    /**
     * @param ElementInterface|MatrixBlockElement $element
     * @return array
     */
    protected function transformElement(ElementInterface $element): array
    {

        return array_merge(
            parent::transformElement($element),
            $this->transformCategory($element)
        );

    }

    /**
     * @param MatrixBlockElement $matrix
     * @return array
     */
    protected function transformCategory(MatrixBlockElement $matrix): array
    {

        return [
            'name' => $matrix->getType()->name
        ];

    }

}