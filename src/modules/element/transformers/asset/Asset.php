<?php

/**
 * @copyright  Copyright (c) Flipbox Digital Limited
 * @license    https://flipboxfactory.com/software/transformer/license
 * @link       https://www.flipboxfactory.com/software/transformer/
 */

namespace flipbox\transformer\modules\element\transformers\asset;

use craft\base\ElementInterface;
use craft\elements\Asset as AssetElement;
use flipbox\transformer\modules\element\transformers\AbstractTransformer;

/**
 * @author Flipbox Factory <hello@flipboxfactory.com>
 * @since 1.0.0
 */
class Asset extends AbstractTransformer
{

    /**
     * @param ElementInterface|AssetElement $element
     * @return array
     */
    protected function transformElement(ElementInterface $element): array
    {
        return array_merge(
            parent::transformElement($element),
            $this->transformAsset($element)
        );

    }

    /**
     * @param AssetElement $asset
     * @return array
     */
    protected function transformAsset(AssetElement $asset): array
    {

        return [
            'name' => $asset->title
        ];

    }

}