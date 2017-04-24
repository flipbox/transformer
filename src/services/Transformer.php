<?php

namespace flipbox\transformer\services;

use craft\helpers\ArrayHelper;
use craft\helpers\Json;
use flipbox\spark\helpers\QueryHelper;
use flipbox\spark\helpers\RecordHelper;
use flipbox\spark\records\Record;
use flipbox\transform\transformers\TransformerInterface;
use flipbox\transformer\records\Transformer as TransformerRecord;
use yii\base\Component;
use yii\base\InvalidConfigException;
use yii\db\ActiveQuery;

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

            foreach ($records as $record) {

                $transformers[$record->handle] = $this->create(
                    $record->toArray()
                );

            }

        }

        return $transformers;

    }

    /**
     * @param array $config
     * @return \yii\db\ActiveQuery
     */
    public function getRecordQuery($config = []): ActiveQuery
    {

        /** @var Record $recordClass */
        $recordClass = static::recordClass();

        $query = $recordClass::find();

        if ($config) {

            QueryHelper::configure(
                $query,
                $config
            );

        }

        return $query;

    }

    /**
     * @param $condition
     * @param string $toScenario
     * @return TransformerRecord|null
     */
    public function findRecordByCondition($condition, string $toScenario = null)
    {

        if (empty($condition)) {
            return null;
        }

        return $this->findRecordByCriteria(
            RecordHelper::conditionToCriteria($condition),
            $toScenario
        );

    }

    /**
     * @param $criteria
     * @param string $toScenario
     * @return TransformerRecord|null
     */
    public function findRecordByCriteria($criteria, string $toScenario = null)
    {

        $query = $this->getRecordQuery($criteria);

        /** @var TransformerRecord $record */
        if ($record = $query->one()) {

            // Set scenario
            if ($toScenario) {
                $record->setScenario($toScenario);
            }

        }

        return $record;

    }


    /**
     * @param array $condition
     * @param string $toScenario
     * @return TransformerRecord[]
     */
    public function findAllRecordsByCondition($condition = [], string $toScenario = null)
    {

        return $this->findAllRecordsByCriteria(
            RecordHelper::conditionToCriteria($condition),
            $toScenario
        );

    }

    /**
     * @param array $criteria
     * @param string $toScenario
     * @return TransformerRecord[]
     */
    public function findAllRecordsByCriteria($criteria = [], string $toScenario = null)
    {

        $query = $this->getRecordQuery($criteria);

        /** @var Record[] $record s */
        $records = $query->all();

        // Set scenario
        if ($toScenario) {

            /** @var Record $record */
            foreach ($records as $record) {

                // Set scenario
                $record->setScenario($toScenario);

            }

        }

        return $records;

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

            $condition = [
                'type' => $type,
                'scope' => $scope
            ];

            if (null !== $siteId) {
                $condition['siteId'] = $siteId;
            }

            $records = $this->findAllRecordsByCondition($condition);

            foreach ($records as $record) {
                $record->config = Json::decodeIfJson($record->config);
            }

            $this->_cacheAllByScope[$key] = $records;

        }

        return $this->_cacheAllByScope[$key];

    }

}
