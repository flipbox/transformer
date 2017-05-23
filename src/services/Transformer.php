<?php

/**
 * @copyright  Copyright (c) Flipbox Digital Limited
 * @license    https://flipboxfactory.com/software/transformer/license
 * @link       https://www.flipboxfactory.com/software/transformer/
 */

namespace flipbox\transformer\services;

use craft\base\ElementInterface;
use craft\base\FieldInterface;
use craft\db\Query;
use craft\helpers\ArrayHelper;
use craft\helpers\Json;
use flipbox\transform\transformers\TransformerInterface;
use flipbox\transformer\events\RegisterTransformers;
use flipbox\transformer\records\Transformer as TransformerRecord;
use flipbox\transformer\Transformer as TransformerPlugin;
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

    use traits\ElementTransformer, traits\ModelTransformer, traits\FieldTransformer;

    /**
     * The event that gets called on the element when registering a transformer
     */
    const EVENT_REGISTER_TRANSFORMERS = 'registerTransformers';

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
     * @return TransformerInterface[]|callable[]
     */
    private function _firstParty(Component $component)
    {

        if ($component instanceof ElementInterface) {
            return $this->firstPartyElements($component);
        }

        if ($component instanceof FieldInterface) {
            return $this->firstPartyFields($component);
        }

        if ($component instanceof Model) {
            return $this->firstPartyModel($component);
        }

        TransformerPlugin::warning(sprintf(
            "First party transformer not found for '%s'",
            get_class($component)
        ));

        return [];

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
