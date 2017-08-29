<?php

/**
 * @copyright  Copyright (c) Flipbox Digital Limited
 * @license    https://flipboxfactory.com/software/transformer/license
 * @link       https://www.flipboxfactory.com/software/transformer/
 */

namespace flipbox\transformer\web\twig\variables;

use craft\base\ElementInterface;
use flipbox\transformer\Transformer;
use Flipbox\Transform\Transformers\TransformerInterface;

/**
 * @author Flipbox Factory <hello@flipboxfactory.com>
 * @since 1.0.0
 */
class Element
{
    /**
     * @param ElementInterface $element
     * @param callable|TransformerInterface|string $transformer
     * @param string $scope
     * @param string $context
     * @param array $config
     * @return array|null
     */
    public function item(
        ElementInterface $element,
        $transformer,
        string $scope = 'global',
        string $context = Transformer::CONTEXT_ARRAY,
        array $config = []
    ) {
        return Transformer::getInstance()->item($element, $transformer, $scope, $context, $config);
    }

    /**
     * @param ElementInterface $element
     * @param callable|TransformerInterface|string $transformer
     * @param string $scope
     * @param string $context
     * @param array $config
     * @return array|null
     */
    public function collection(
        ElementInterface $element,
        $transformer,
        string $scope = 'global',
        string $context = Transformer::CONTEXT_ARRAY,
        array $config = []
    ) {
        return Transformer::getInstance()->collection($element, $transformer, $scope, $context, $config);
    }
}
