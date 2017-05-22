<?php

/**
 * @copyright  Copyright (c) Flipbox Digital Limited
 * @license    https://flipboxfactory.com/software/transformer/license
 * @link       https://www.flipboxfactory.com/software/transformer/
 */

namespace flipbox\transformer\services;

use craft\base\ElementInterface;
use craft\base\Field;
use craft\base\FieldInterface;
use craft\db\Query;
use craft\elements\Asset as AssetElement;
use craft\elements\Category as CategoryElement;
use craft\elements\Entry as EntryElement;
use craft\elements\MatrixBlock as MatrixBlockElement;
use craft\elements\Tag as TagElement;
use craft\elements\User as UserElement;
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
use craft\helpers\ArrayHelper;
use craft\helpers\Json;
use craft\models\EntryType as EntryTypeModel;
use craft\models\Section as SectionModel;
use flipbox\transform\transformers\TransformerInterface;
use flipbox\transformer\events\RegisterTransformers;
use flipbox\transformer\records\Transformer as TransformerRecord;
use flipbox\transformer\Transformer as TransformerPlugin;
use flipbox\transformer\transformers\element\asset\Asset as AssetTransformer;
use flipbox\transformer\transformers\element\category\Category as CategoryTransformer;
use flipbox\transformer\transformers\element\entry\Entry as EntryTransformer;
use flipbox\transformer\transformers\element\matrix\Block as MatrixBlockTransformer;
use flipbox\transformer\transformers\element\tag\Tag as TagTransformer;
use flipbox\transformer\transformers\element\user\User as UserTransformer;
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
use flipbox\transformer\transformers\model\entry\Section as EntrySectionTransformer;
use flipbox\transformer\transformers\model\entry\Type as EntryTypeTransformer;
use yii\base\Component;
use yii\base\InvalidConfigException;
use yii\base\Model;
use yii\db\Exception;

/**
 * @author Flipbox Factory <hello@flipboxfactory.com>
 * @since 1.0.0
 */
class Transformer extends Component
{

    /**
     * The event that gets called on the element when registering a transformer
     */
    const EVENT_REGISTER_TRANSFORMERS = 'registerTransformers';

    /**
     * @var [TransformerInterface[]]
     */
    protected $_cacheAllByScope = [];

    /**
     * @return string
     */
    public static function objectClassInstance(): string
    {
        return TransformerInterface::class;
    }

    /**
     * @return string
     */
    public static function recordClass(): string
    {
        return TransformerRecord::class;
    }

    /**
     * @param string $identifier
     * @param Component $component
     * @param string $scope
     * @param int|null $siteId
     * @return TransformerInterface
     * @throws Exception
     */
    public function get(string $identifier, Component $component, string $scope = 'global', int $siteId = null)
    {

        if (!$transformer = $this->find($identifier, $component, $scope, $siteId)) {
            throw new Exception("Transformer not found");
        }

        return $transformer;

    }

    /**
     * @param string $identifier
     * @param Component $component
     * @param string $scope
     * @param int|null $siteId
     * @return TransformerInterface|null
     */
    public function find(string $identifier, Component $component, string $scope = 'global', int $siteId = null)
    {

        return ArrayHelper::getValue(
            $this->findAll($component, $scope, $siteId),
            $identifier
        );

    }

    /**
     * @param Component $component
     * @param string $scope
     * @param int|null $siteId
     * @return \callable[]|TransformerInterface[]
     */
    public function findAll(Component $component, string $scope = 'global', int $siteId = null)
    {

        /** @var Model $model * */

        $transformers = array_merge(
            $this->_firstParty($component),
            $this->_storage($component, $scope, $siteId)
        );

        $event = new RegisterTransformers([
            'transformers' => $transformers
        ]);

        $component->trigger(
            self::EVENT_REGISTER_TRANSFORMERS . ':' . $scope,
            $event
        );

        return $event->getTransformers();

    }

    /**
     * @param Component $component
     * @return TransformerInterface[]
     */
    private function _firstParty(Component $component)
    {

        if ($component instanceof ElementInterface) {
            return $this->_firstPartyElements($component);
        }

        if ($component instanceof FieldInterface) {
            return $this->_firstPartyFields($component);
        }

        if ($component instanceof Model) {
            return $this->_firstPartyModel($component);
        }

        TransformerPlugin::warning(sprintf(
            "First party transformer not found for '%s'",
            get_class($component)
        ));

        return [];

    }

    /**
     * @param ElementInterface $element
     * @return TransformerInterface[]|callable[]
     */
    private function _firstPartyElements(ElementInterface $element)
    {

        $transformers = [];

        switch (get_class($element)) {

            case AssetElement::class: {
                $transformers['default'] = new AssetTransformer();
                break;
            }

            case CategoryElement::class: {
                $transformers['default'] = new CategoryTransformer();
                break;
            }

            case EntryElement::class: {
                $transformers['default'] = new EntryTransformer();
                break;
            }

            case MatrixBlockElement::class: {
                $transformers['default'] = new MatrixBlockTransformer();
                break;
            }

            case TagElement::class: {
                $transformers['default'] = new TagTransformer();
                break;
            }

            case UserElement::class: {
                $transformers['default'] = new UserTransformer();
                break;
            }

            default: {
                TransformerPlugin::warning(sprintf(
                    "First party transformer not found for element '%s'",
                    get_class($element)
                ));
            }

        }

        return $transformers;

    }

    /**
     * @param FieldInterface|Field $field
     * @return TransformerInterface[]|callable[]
     */
    private function _firstPartyFields(FieldInterface $field)
    {

        $transformers = [];

        switch (get_class($field)) {

            case AssetsField::class: {
                /** @var AssetsField $field * */
                $transformers['default'] = new AssetCollectionResource($field);
                break;
            }

            case CategoriesField::class: {
                /** @var CategoriesField $field * */
                $transformers['default'] = new CategoryCollectionResource($field);
                break;
            }

            case CheckboxesField::class: {
                /** @var CheckboxesField $field * */
                $transformers['default'] = new CheckboxesTransformer($field);
                break;
            }

            case ColorField::class: {
                /** @var ColorField $field * */
                $transformers['default'] = new ColorTransformer($field);
                break;
            }

            case DateField::class: {
                /** @var DateField $field * */
                $transformers['default'] = new DateTransformer($field);
                break;
            }

            case DropdownField::class: {
                /** @var DropdownField $field * */
                $transformers['default'] = new DropdownTransformer($field);
                break;
            }

            case EntriesField::class: {
                /** @var EntriesField $field * */
                $transformers['default'] = new EntryCollectionResource($field);
                break;
            }

            case LightswitchField::class: {
                /** @var LightswitchField $field * */
                $transformers['default'] = new LightswitchTransformer($field);
                break;
            }

            case MatrixField::class: {
                /** @var MatrixField $field * */
                $transformers['default'] = new MatrixCollectionResource($field);
                break;
            }

            case MultiSelectField::class: {
                /** @var MultiSelectField $field * */
                $transformers['default'] = new MultiSelectTransformer($field);
                break;
            }

            case NumberField::class: {
                /** @var NumberField $field * */
                $transformers['default'] = new NumberTransformer($field);
                break;
            }

            case PlainTextField::class: {
                /** @var PlainTextField $field * */
                $transformers['default'] = new PlainTextTransformer($field);
                break;
            }

            case PositionSelectField::class: {
                /** @var PositionSelectField $field * */
                $transformers['default'] = new PositionSelectTransformer($field);
                break;
            }

            case RadioButtonsField::class: {
                /** @var RadioButtonsField $field * */
                $transformers['default'] = new RadioButtonsTransformer($field);
                break;
            }

            case RichTextField::class: {
                /** @var RichTextField $field * */
                $transformers['default'] = new RichTextTransformer($field);
                break;
            }

            case TableField::class: {
                /** @var TableField $field * */
                $transformers['default'] = new TableTransformer($field);
                break;
            }

            case TagsField::class: {
                /** @var TagsField $field * */
                $transformers['default'] = new TagCollectionResource($field);
                break;
            }

            case UsersField::class: {
                /** @var UsersField $field * */
                $transformers['default'] = new UserCollectionResource($field);
                break;
            }

            default: {
                TransformerPlugin::warning(sprintf(
                    "First party transformer not found for field '%s'",
                    get_class($field)
                ));
            }

        }

        return $transformers;

    }

    /**
     * @param Model $model
     * @return TransformerInterface[]|callable[][]
     */
    private function _firstPartyModel(Model $model)
    {

        $transformers = [];

        switch (get_class($model)) {

            case SectionModel::class: {
                $transformers['default'] = new EntrySectionTransformer();
                break;
            }

            case EntryTypeModel::class: {
                $transformers['default'] = new EntryTypeTransformer();
                break;
            }

            default: {
                TransformerPlugin::warning(sprintf(
                    "First party transformer not found for model '%s'",
                    get_class($model)
                ));
            }

        }

        return $transformers;

    }

    /**
     * @param Component $component
     * @param string $scope
     * @param int|null $siteId
     * @return TransformerInterface[]
     */
    private function _storage(Component $component, string $scope = 'global', int $siteId = null)
    {

        $condition = [
            'type' => get_class($component),
            'scope' => $scope,
        ];

        if (null !== $siteId) {
            $condition['siteId'] = $siteId;
        }

        return $this->_storageByCondition($condition);

    }

    /**
     * @param array $condition
     * @return TransformerInterface[]
     */
    private function _storageByCondition(array $condition = [])
    {

        /** @var TransformerRecord $recordClass */
        $recordClass = static::recordClass();

        // Find all of the installed plugins
        $records = (new Query())
            ->select([
                'handle',
                'class',
                'config'
            ])
            ->from([$recordClass::tableName()])
            ->where($condition)
            ->all();

        $transformers = [];

        foreach ($records as $lcHandle => &$row) {
            $row['config'] = Json::decode($row['config']);
            $transformers[] = $this->create($row);
        }

        return $transformers;

    }

    /**
     * @inheritdoc
     */
    public function create($config = []): TransformerInterface
    {

        // Force Array
        if (!is_array($config)) {
            $config = ArrayHelper::toArray($config, [], false);
        }

        $class = ArrayHelper::remove($config, 'class');

        if (null === $class || !is_subclass_of($class, static::objectClassInstance())) {

            throw new InvalidConfigException(
                sprintf(
                    "The class '%s' must be an instance of '%s'",
                    (string)$class,
                    (string)static::objectClassInstance()
                )
            );

        }

        return new $class(
            ArrayHelper::remove($config, 'config')
        );

    }

}
