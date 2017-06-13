<?php

/**
 * @copyright  Copyright (c) Flipbox Digital Limited
 * @license    https://flipboxfactory.com/software/transformer/license
 * @link       https://www.flipboxfactory.com/software/transformer/
 */

namespace flipbox\transformer\services\traits;

use craft\elements\Asset as AssetElement;
use craft\elements\Category as CategoryElement;
use craft\elements\Entry as EntryElement;
use craft\elements\MatrixBlock as MatrixBlockElement;
use craft\elements\Tag as TagElement;
use craft\elements\User as UserElement;
use Flipbox\Transform\Transformers\TransformerInterface;
use flipbox\transformer\Transformer as TransformerPlugin;
use flipbox\transformer\transformers\element\asset\Asset as AssetTransformer;
use flipbox\transformer\transformers\element\category\Category as CategoryTransformer;
use flipbox\transformer\transformers\element\entry\Entry as EntryTransformer;
use flipbox\transformer\transformers\element\matrix\Block as MatrixBlockTransformer;
use flipbox\transformer\transformers\element\tag\Tag as TagTransformer;
use flipbox\transformer\transformers\element\user\User as UserTransformer;

/**
 * @author Flipbox Factory <hello@flipboxfactory.com>
 * @since 1.0.0
 */
trait ElementTransformer
{

    /**
     * @param string $element
     * @return TransformerInterface[]|callable[]
     */
    protected function firstPartyElements(string $element)
    {
        $transformers = [];
        switch ($element) {
            case AssetElement::class:
                $transformers['default'] = new AssetTransformer();
                break;
            case CategoryElement::class:
                $transformers['default'] = new CategoryTransformer();
                break;
            case EntryElement::class:
                $transformers['default'] = new EntryTransformer();
                break;
            case MatrixBlockElement::class:
                $transformers['default'] = new MatrixBlockTransformer();
                break;
            case TagElement::class:
                $transformers['default'] = new TagTransformer();
                break;
            case UserElement::class:
                $transformers['default'] = new UserTransformer();
                break;
            default:
                TransformerPlugin::warning(
                    sprintf(
                        "First party transformer not found for element '%s'",
                        get_class($element)
                    )
                );
        }

        return $transformers;
    }
}
