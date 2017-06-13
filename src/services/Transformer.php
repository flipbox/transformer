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
use Flipbox\Transform\Transformers\TransformerInterface;
use flipbox\transformer\events\RegisterTransformers;
use flipbox\transformer\records\Transformer as TransformerRecord;
use flipbox\transformer\Transformer as TransformerPlugin;
use yii\base\Component;
use yii\base\InvalidConfigException;
use yii\base\Model;
use yii\db\Exception;
use flipbox\transformer\helpers\Transformer as TransformerHelper;

/**
 * @author Flipbox Factory <hello@flipboxfactory.com>
 * @since 1.0.0
 */
class Transformer extends Component
{

    use traits\ElementTransformer, traits\ModelTransformer, traits\FieldTransformer;

    /**
     * @param string   $identifier
     * @param string   $class
     * @param string   $scope
     * @param string   $context
     * @param int|null $siteId
     * @return callable|TransformerInterface
     * @throws Exception
     */
    public function get(
        string $identifier,
        string $class,
        string $scope = 'global',
        string $context = TransformerPlugin::CONTEXT_ARRAY,
        int $siteId = null
    ) {
        if (!$transformer = $this->find($identifier, $class, $scope, $context, $siteId)) {
            throw new Exception("Transformer not found");
        }

        return $transformer;
    }

    /**
     * @param string   $identifier
     * @param string   $class
     * @param string   $scope
     * @param string   $context
     * @param int|null $siteId
     * @return callable|TransformerInterface|null
     */
    public function find(
        string $identifier,
        string $class,
        string $scope = 'global',
        string $context = TransformerPlugin::CONTEXT_ARRAY,
        int $siteId = null
    ) {
        return ArrayHelper::getValue(
            $this->findAll($class, $scope, $context, $siteId),
            $identifier
        );
    }

    /**
     * @param string   $class
     * @param string   $scope
     * @param string   $context
     * @param int|null $siteId
     * @return \callable[]|TransformerInterface[]
     * @throws \yii\base\Exception
     */
    public function findAll(
        string $class,
        string $scope = 'global',
        string $context = TransformerPlugin::CONTEXT_ARRAY,
        int $siteId = null
    ) {
        if (!is_subclass_of($class, Component::class)) {
            throw new \yii\base\Exception("Invalid component");
        }

        $transformers = array_merge(
            $this->firstParty($class),
            $this->storage($class, $scope, $context, $siteId)
        );

        $event = new RegisterTransformers(
            [
            'transformers' => $transformers
            ]
        );

        $event->trigger(
            $class,
            TransformerPlugin::eventName($scope, $context),
            $event
        );

        return $event->getTransformers();
    }

    /**
     * @param string $class
     * @return TransformerInterface[]|callable[]
     */
    private function firstParty(string $class)
    {
        if (is_subclass_of($class, ElementInterface::class)) {
            return $this->firstPartyElements($class);
        }

        if (is_subclass_of($class, FieldInterface::class)) {
            return $this->firstPartyFields($class);
        }

        if (is_subclass_of($class, Model::class)) {
            return $this->firstPartyModel($class);
        }

        TransformerPlugin::warning(
            sprintf(
                "First party transformer not found for '%s'",
                get_class($class)
            )
        );

        return [];
    }

    /**
     * @param string   $class
     * @param string   $scope
     * @param string   $context
     * @param int|null $siteId
     * @return TransformerInterface[]
     */
    private function storage(
        string $class,
        string $scope = 'global',
        string $context = TransformerPlugin::CONTEXT_ARRAY,
        int $siteId = null
    ) {
        $condition = [
            'type' => $class,
            'scope' => $scope,
            'context' => $context,
        ];

        if (null !== $siteId) {
            $condition['siteId'] = $siteId;
        }

        return $this->storageByCondition($condition);
    }

    /**
     * @param array $condition
     * @return TransformerInterface[]
     */
    private function storageByCondition(array $condition = [])
    {
        // Find all of the installed plugins
        $records = (new Query())
            ->select(
                [
                'handle',
                'class',
                'config'
                ]
            )
            ->from([TransformerRecord::tableName()])
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
     * @param array $config
     * @return TransformerInterface
     * @throws InvalidConfigException
     */
    public function create(array $config = []): TransformerInterface
    {
        $class = ArrayHelper::remove($config, 'class');

        if (null === $class || !TransformerHelper::isTransformerClass($class)) {
            throw new InvalidConfigException(
                sprintf(
                    "The class '%s' must be an instance of '%s'",
                    (string)$class,
                    (string)TransformerInterface::class
                )
            );
        }

        return new $class(
            ArrayHelper::remove($config, 'config')
        );
    }
}
