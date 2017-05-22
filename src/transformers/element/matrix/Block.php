<?php

/**
 * @copyright  Copyright (c) Flipbox Digital Limited
 * @license    https://flipboxfactory.com/software/transformer/license
 * @link       https://www.flipboxfactory.com/software/transformer/
 */

namespace flipbox\transformer\transformers\element\matrix;

use craft\base\ElementInterface;
use craft\elements\MatrixBlock as MatrixBlockElement;
use flipbox\transformer\transformers\element\AbstractTransformer;

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
            $this->transformBlock($element)
        );

    }

    /**
     * @param MatrixBlockElement $matrix
     * @return array
     */
    protected function transformBlock(MatrixBlockElement $matrix): array
    {

        return [
            'name' => $matrix->getType()->name
        ];

    }

}