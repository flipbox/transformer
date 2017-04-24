<?php

/**
 * @copyright  Copyright (c) Flipbox Digital Limited
 * @license    https://flipboxfactory.com/software/transformer/license
 * @link       https://www.flipboxfactory.com/software/transformer/
 */

namespace flipbox\transformer\services;

use craft\db\Query;
use craft\helpers\ArrayHelper;
use craft\helpers\Json;
use flipbox\transform\transformers\TransformerInterface;
use flipbox\transformer\records\Transformer as TransformerRecord;
use yii\base\Component;
use yii\base\InvalidConfigException;

/**
 * @author Flipbox Factory <hello@flipboxfactory.com>
 * @since 1.0.0
 */
class Transformer extends Component
{

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
     * @param string $type
     * @param string $scope
     * @param int|null $siteId
     * @return TransformerInterface[]
     */
    public function findAllByTypeAndScope(string $type, string $scope, int $siteId = null)
    {

        $transformers = [];

        // Find record in db
        if ($records = $this->findAllRecordsByTypeAndScope($type, $scope, $siteId)) {

            foreach ($records as $handle => $record) {

                $transformers[$handle] = $this->create($record);

            }

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


    /**
     * @param string $type
     * @param string $scope
     * @param int|null $siteId
     * @return TransformerRecord[]
     */
    public function findAllRecordsByTypeAndScope(string $type, string $scope, int $siteId = null)
    {

        $key = $type . ':' . $scope;

        // Check addToCache
        if (!array_key_exists($key, $this->_cacheAllByScope)) {

            $this->_cacheAllByScope[$key] = [];

            /** @var TransformerRecord $recordClass */
            $recordClass = static::recordClass();

            $condition = [
                'type' => $type,
                'scope' => $scope
            ];

            if (null !== $siteId) {
                $condition['siteId'] = $siteId;
            }

            // Find all of the installed plugins
            $records = (new Query())
                ->select([
                    'handle',
                    'class',
                    'config'
                ])
                ->from([$recordClass::tableName()])
                ->andWhere($condition)
                ->indexBy('handle')
                ->all();

            foreach ($records as $lcHandle => &$row) {
                $records[$lcHandle]['config'] = Json::decode($row['config']);
            }

            $this->_cacheAllByScope[$key] = $records;

            unset($records);

        }

        return $this->_cacheAllByScope[$key];

    }

}
