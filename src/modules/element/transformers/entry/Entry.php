<?php

/**
 * @copyright  Copyright (c) Flipbox Digital Limited
 * @license    https://flipboxfactory.com/software/transformer/license
 * @link       https://www.flipboxfactory.com/software/transformer/
 */

namespace flipbox\transformer\modules\element\transformers\entry;

use craft\base\ElementInterface;
use craft\elements\Entry as EntryElement;
use flipbox\transformer\modules\element\transformers\AbstractTransformer;
use flipbox\transformer\Transformer as TransformerPlugin;

/**
 * @author Flipbox Factory <hello@flipboxfactory.com>
 * @since 1.0.0
 */
class Entry extends AbstractTransformer
{

    /**
     * @param ElementInterface|EntryElement $element
     * @return array
     */
    protected function transformElement(ElementInterface $element): array
    {

        return array_merge(
            parent::transformElement($element),
            $this->transformEntry($element)
        );

    }

    /**
     * @param EntryElement $entry
     * @return array
     */
    protected function transformEntry(EntryElement $entry): array
    {
        return [
            'title' => $entry->title,
            'section' => TransformerPlugin::getInstance()->getModel()->getTransformer()->item(
                $entry->getSection()
            ),
            'type' => TransformerPlugin::getInstance()->getModel()->getTransformer()->item(
                $entry->getType()
            )
        ];
    }

}