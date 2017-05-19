<?php

/**
 * @copyright  Copyright (c) Flipbox Digital Limited
 * @license    https://flipboxfactory.com/software/transformer/license
 * @link       https://www.flipboxfactory.com/software/transformer/
 */

namespace flipbox\transformer\modules\field\services;

use craft\base\Component;
use craft\base\Field;
use craft\base\FieldInterface;
use craft\fields\Assets;
use craft\fields\Categories;
use craft\fields\Checkboxes;
use craft\fields\Color;
use craft\fields\Date;
use craft\fields\Dropdown;
use craft\fields\Entries;
use craft\fields\Lightswitch;
use craft\fields\Matrix;
use craft\fields\MultiSelect;
use craft\fields\Number;
use craft\fields\PlainText;
use craft\fields\PositionSelect;
use craft\fields\RadioButtons;
use craft\fields\RichText;
use craft\fields\Table;
use craft\fields\Tags;
use craft\fields\Users;
use craft\helpers\ArrayHelper;
use flipbox\transform\resources\ResourceInterface;
use flipbox\transform\transformers\TransformerInterface;
use flipbox\transformer\events\RegisterTransformers;
use flipbox\transformer\modules\field\transformers\asset\CollectionResource as AssetCollectionResource;
use flipbox\transformer\modules\field\transformers\category\CollectionResource as CategoryCollectionResource;
use flipbox\transformer\modules\field\transformers\Checkboxes as CheckboxesTransformer;
use flipbox\transformer\modules\field\transformers\CollectionResource;
use flipbox\transformer\modules\field\transformers\Color as ColorTransformer;
use flipbox\transformer\modules\field\transformers\Date as DateTransformer;
use flipbox\transformer\modules\field\transformers\Dropdown as DropdownTransformer;
use flipbox\transformer\modules\field\transformers\entry\CollectionResource as EntryCollectionResource;
use flipbox\transformer\modules\field\transformers\ItemResource;
use flipbox\transformer\modules\field\transformers\Lightswitch as LightswitchTransformer;
use flipbox\transformer\modules\field\transformers\matrix\CollectionResource as MatrixCollectionResource;
use flipbox\transformer\modules\field\transformers\MultiSelect as MultiSelectTransformer;
use flipbox\transformer\modules\field\transformers\Number as NumberTransformer;
use flipbox\transformer\modules\field\transformers\PlainText as PlainTextTransformer;
use flipbox\transformer\modules\field\transformers\PositionSelect as PositionSelectTransformer;
use flipbox\transformer\modules\field\transformers\RadioButtons as RadioButtonsTransformer;
use flipbox\transformer\modules\field\transformers\RichText as RichTextTransformer;
use flipbox\transformer\modules\field\transformers\Table as TableTransformer;
use flipbox\transformer\modules\field\transformers\tag\CollectionResource as TagCollectionResource;
use flipbox\transformer\modules\field\transformers\User as UserTransformer;
use flipbox\transformer\modules\field\transformers\user\CollectionResource as UserCollectionResource;
use flipbox\transformer\Transformer as TransformerPlugin;
use yii\base\Exception;

/**
 * @author Flipbox Factory <hello@flipboxfactory.com>
 * @since 1.0.0
 */
class Transformer extends Component
{

    /**
     * The event that gets called on the element when registering a transformer
     */
    const EVENT_REGISTER_TRANSFORMERS = 'registerFieldTransformers';

    /**
     * @param FieldInterface $field
     * @param string $transformer
     * @return CollectionResource
     */
    public function collection(FieldInterface $field, $transformer = 'default')
    {
        return new CollectionResource($field, ['transformer' => $transformer]);
    }

    /**
     * @param FieldInterface $field
     * @param string $transformer
     * @return ItemResource
     */
    public function item(FieldInterface $field, $transformer = 'default')
    {
        return new ItemResource($field, ['transformer' => $transformer]);
    }

    /**
     * @param FieldInterface|Field $field
     * @return mixed
     */
    public function getAll(FieldInterface $field)
    {

        $transformers = array_merge(
            $this->_firstParty($field),
            $this->_database($field)
        );

        $event = new RegisterTransformers([
            'transformers' => $transformers
        ]);

        $field->trigger(
            self::EVENT_REGISTER_TRANSFORMERS,
            $event
        );

        return $event->getTransformers();

    }

    /**
     * @param string $identifier
     * @param FieldInterface $field
     * @return ResourceInterface|TransformerInterface|callable
     */
    public function find(string $identifier, FieldInterface $field)
    {

        return ArrayHelper::getValue(
            $this->getAll($field),
            $identifier
        );

    }

    /**
     * @param string $identifier
     * @param FieldInterface $field
     * @return ResourceInterface|TransformerInterface|callable
     * @throws Exception
     */
    public function get(string $identifier, FieldInterface $field)
    {

        if (!$transform = $this->find($identifier, $field)) {
            throw new Exception("Transformer not found");
        }

        return $transform;

    }

    /**
     * @param FieldInterface|Field $field
     * @return TransformerInterface[]|callable[]
     */
    private function _firstParty(FieldInterface $field)
    {

        $transformers = [];

        switch (get_class($field)) {

            case Assets::class: {
                /** @var Assets $field * */
                $transformers['default'] = new AssetCollectionResource($field);
                break;
            }

            case Categories::class: {
                /** @var Categories $field * */
                $transformers['default'] = new CategoryCollectionResource($field);
                break;
            }

            case Checkboxes::class: {
                /** @var Checkboxes $field * */
                $transformers['default'] = new CheckboxesTransformer($field);
                break;
            }

            case Color::class: {
                /** @var Color $field * */
                $transformers['default'] = new ColorTransformer($field);
                break;
            }

            case Date::class: {
                /** @var Date $field * */
                $transformers['default'] = new DateTransformer($field);
                break;
            }

            case Dropdown::class: {
                /** @var Dropdown $field * */
                $transformers['default'] = new DropdownTransformer($field);
                break;
            }

            case Entries::class: {
                /** @var Entries $field * */
                $transformers['default'] = new EntryCollectionResource($field);
                break;
            }

            case Lightswitch::class: {
                /** @var Lightswitch $field * */
                $transformers['default'] = new LightswitchTransformer($field);
                break;
            }

            case Matrix::class: {
                /** @var Matrix $field * */
                $transformers['default'] = new MatrixCollectionResource($field);
                break;
            }

            case MultiSelect::class: {
                /** @var MultiSelect $field * */
                $transformers['default'] = new MultiSelectTransformer($field);
                break;
            }

            case Number::class: {
                /** @var Number $field * */
                $transformers['default'] = new NumberTransformer($field);
                break;
            }

            case PlainText::class: {
                /** @var PlainText $field * */
                $transformers['default'] = new PlainTextTransformer($field);
                break;
            }

            case PositionSelect::class: {
                /** @var PositionSelect $field * */
                $transformers['default'] = new PositionSelectTransformer($field);
                break;
            }

            case RadioButtons::class: {
                /** @var RadioButtons $field * */
                $transformers['default'] = new RadioButtonsTransformer($field);
                break;
            }

            case RichText::class: {
                /** @var RichText $field * */
                $transformers['default'] = new RichTextTransformer($field);
                break;
            }

            case Table::class: {
                /** @var Table $field * */
                $transformers['default'] = new TableTransformer($field);
                break;
            }

            case Tags::class: {
                /** @var Tags $field * */
                $transformers['default'] = new TagCollectionResource($field);
                break;
            }

            case Users::class: {
                /** @var Users $field * */
                $transformers['default'] = new UserCollectionResource($field);
                break;
            }

        }

        return $transformers;

    }

    /**
     * @param FieldInterface $field
     * @return TransformerInterface[]
     */
    private function _database(FieldInterface $field)
    {
        return TransformerPlugin::getInstance()->getTransformer()->findAllByTypeAndScope(
            get_class($field),
            self::EVENT_REGISTER_TRANSFORMERS
        );
    }

}