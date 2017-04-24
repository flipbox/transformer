<?php

namespace flipbox\transformer\modules\element\transformers\asset;

use craft\base\ElementInterface;
use craft\elements\Asset as AssetElement;
use flipbox\transformer\modules\element\transformers\AbstractTransformer;

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