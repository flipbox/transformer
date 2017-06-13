<?php

/**
 * @copyright  Copyright (c) Flipbox Digital Limited
 * @license    https://flipboxfactory.com/software/transformer/license
 * @link       https://www.flipboxfactory.com/software/transformer/
 */

namespace flipbox\transformer\services\traits;

use craft\fields\Assets as AssetsField;
use craft\fields\Categories as CategoriesField;
use craft\fields\Checkboxes as CheckboxesField;
use craft\fields\Color as ColorField;
use craft\fields\Date as DateField;
use craft\fields\Dropdown as DropdownField;
use craft\fields\Entries as EntriesField;
use craft\fields\Lightswitch as LightswitchField;
use craft\fields\Matrix as MatrixField;
use craft\fields\MultiSelect as MultiSelectField;
use craft\fields\Number as NumberField;
use craft\fields\PlainText as PlainTextField;
use craft\fields\PositionSelect as PositionSelectField;
use craft\fields\RadioButtons as RadioButtonsField;
use craft\fields\RichText as RichTextField;
use craft\fields\Table as TableField;
use craft\fields\Tags as TagsField;
use craft\fields\Users as UsersField;
use Flipbox\Transform\Transformers\TransformerInterface;
use flipbox\transformer\Transformer as TransformerPlugin;
use flipbox\transformer\transformers\field\asset\CollectionResource as AssetCollectionResource;
use flipbox\transformer\transformers\field\category\CollectionResource as CategoryCollectionResource;
use flipbox\transformer\transformers\field\Checkboxes as CheckboxesTransformer;
use flipbox\transformer\transformers\field\Color as ColorTransformer;
use flipbox\transformer\transformers\field\Date as DateTransformer;
use flipbox\transformer\transformers\field\Dropdown as DropdownTransformer;
use flipbox\transformer\transformers\field\entry\CollectionResource as EntryCollectionResource;
use flipbox\transformer\transformers\field\Lightswitch as LightswitchTransformer;
use flipbox\transformer\transformers\field\matrix\CollectionResource as MatrixCollectionResource;
use flipbox\transformer\transformers\field\MultiSelect as MultiSelectTransformer;
use flipbox\transformer\transformers\field\Number as NumberTransformer;
use flipbox\transformer\transformers\field\PlainText as PlainTextTransformer;
use flipbox\transformer\transformers\field\PositionSelect as PositionSelectTransformer;
use flipbox\transformer\transformers\field\RadioButtons as RadioButtonsTransformer;
use flipbox\transformer\transformers\field\RichText as RichTextTransformer;
use flipbox\transformer\transformers\field\Table as TableTransformer;
use flipbox\transformer\transformers\field\tag\CollectionResource as TagCollectionResource;
use flipbox\transformer\transformers\field\user\CollectionResource as UserCollectionResource;

/**
 * @author Flipbox Factory <hello@flipboxfactory.com>
 * @since 1.0.0
 */
trait FieldTransformer
{

    /**
     * @param string $field
     * @return TransformerInterface[]|callable[]
     */
    protected function firstPartyFields(string $field)
    {

        $transformers = [];

        switch ($field) {

        case AssetsField::class:
            /**
             * @var AssetsField $field
             */
            $transformers['default'] = new AssetCollectionResource($field);
            break;

        case CategoriesField::class:
            /**
            * @var CategoriesField $field *
            */
            $transformers['default'] = new CategoryCollectionResource($field);
            break;

        case CheckboxesField::class:
            /**
            * @var CheckboxesField $field *
            */
            $transformers['default'] = new CheckboxesTransformer($field);
            break;

        case ColorField::class:
            /**
            * @var ColorField $field *
            */
            $transformers['default'] = new ColorTransformer($field);
            break;

        case DateField::class:
            /**
            * @var DateField $field *
            */
            $transformers['default'] = new DateTransformer($field);
            break;

        case DropdownField::class:
            /**
            * @var DropdownField $field *
            */
            $transformers['default'] = new DropdownTransformer($field);
            break;

        case EntriesField::class:
            /**
            * @var EntriesField $field *
            */
            $transformers['default'] = new EntryCollectionResource($field);
            break;

        case LightswitchField::class:
            /**
            * @var LightswitchField $field *
            */
            $transformers['default'] = new LightswitchTransformer($field);
            break;

        case MatrixField::class:
            /**
            * @var MatrixField $field *
            */
            $transformers['default'] = new MatrixCollectionResource($field);
            break;

        case MultiSelectField::class:
            /**
            * @var MultiSelectField $field *
            */
            $transformers['default'] = new MultiSelectTransformer($field);
            break;

        case NumberField::class:
            /**
            * @var NumberField $field *
            */
            $transformers['default'] = new NumberTransformer($field);
            break;

        case PlainTextField::class:
            /**
            * @var PlainTextField $field *
            */
            $transformers['default'] = new PlainTextTransformer($field);
            break;

        case PositionSelectField::class:
            /**
            * @var PositionSelectField $field *
            */
            $transformers['default'] = new PositionSelectTransformer($field);
            break;

        case RadioButtonsField::class:
            /**
            * @var RadioButtonsField $field *
            */
            $transformers['default'] = new RadioButtonsTransformer($field);
            break;

        case RichTextField::class:
            /**
            * @var RichTextField $field *
            */
            $transformers['default'] = new RichTextTransformer($field);
            break;

        case TableField::class:
            /**
            * @var TableField $field *
            */
            $transformers['default'] = new TableTransformer($field);
            break;

        case TagsField::class:
            /**
            * @var TagsField $field *
            */
            $transformers['default'] = new TagCollectionResource($field);
            break;

        case UsersField::class:
            /**
            * @var UsersField $field *
            */
            $transformers['default'] = new UserCollectionResource($field);
            break;

        default:
            TransformerPlugin::warning(
                sprintf(
                    "First party transformer not found for field '%s'",
                    get_class($field)
                )
            );

        }

        return $transformers;
    }
}
