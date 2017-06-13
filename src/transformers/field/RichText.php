<?php

/**
 * @copyright  Copyright (c) Flipbox Digital Limited
 * @license    https://flipboxfactory.com/software/transformer/license
 * @link       https://www.flipboxfactory.com/software/transformer/
 */

namespace flipbox\transformer\transformers\field;

use craft\fields\data\RichTextData;
use craft\fields\RichText as RichTextField;
use Flipbox\Transform\Resources\Item;
use Flipbox\Transform\Scope;

/**
 * @author Flipbox Factory <hello@flipboxfactory.com>
 * @since 1.0.0
 *
 * @property RichTextField $field
 * @property RichTextData $data
 */
class RichText extends AbstractTransformer
{
    /**
     * @param RichTextField $field
     * @param array         $config
     */
    public function __construct(RichTextField $field, array $config = [])
    {
        parent::__construct($field, $config);
    }

    /**
     * @inheritdoc
     */
    public function transform(Scope $scope, string $identifier = null)
    {
        $resource = new Item(
            $scope->childScope($identifier)
        );

        return $resource->transform(
            function (RichTextData $data) {
                return [
                    'content' => $data->getParsedContent(),
                    'pages' => $data->getTotalPages()
                ];
            },
            $this->data
        );
    }
}
