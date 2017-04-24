<?php

namespace flipbox\transformer\modules\element\transformers;

use craft\base\ElementInterface;
use craft\elements\db\ElementQuery;
use craft\helpers\ArrayHelper;
use flipbox\spark\helpers\QueryHelper;
use flipbox\transform\Scope;
use flipbox\transformer\transformers\AbstractItemResource as BaseAbstractItemResource;
use flipbox\transform\transformers\TransformerInterface;
use flipbox\transformer\Plugin;
use yii\base\Exception;

abstract class AbstractItemResource extends BaseAbstractItemResource
{

    /**
     * @return ElementInterface
     */
    protected abstract function element(): ElementInterface;

    /**
     * @inheritdoc
     */
    protected function getData(Scope $scope)
    {

        /** @var ElementQuery $query */
        $query = parent::getData($scope);

        if ($queryParams = $this->getQueryParams($scope)) {
            QueryHelper::configure(
                $query,
                $queryParams
            );
        }

        return $query;

    }

    /**
     * @param Scope $scope
     * @return array
     */
    protected function getQueryParams(Scope $scope): array
    {

        $params = [];

        $paramBag = $scope->getParams();

        foreach ($this->queryParams() as $param) {

            if ($value = $paramBag->get($param)) {

                $params[$param] = $value;

            }

        }

        return $params;

    }

    /**
     * @return array
     */
    protected function queryParams(): array
    {
        return ['status', 'limit'];
    }

    /**
     * @param string $handle
     * @return TransformerInterface
     * @throws Exception
     */
    protected function resolveTransformerByHandle(string $handle): TransformerInterface
    {

        $transformer = Plugin::getInstance()->getElement()->getTransformer()->find(
            $handle,
            $this->element()
        );

        if (null === $transformer) {

            throw new Exception(sprintf(
                "Transformer '%s' does not exist on resource '%s'.",
                (string)$handle,
                (string)get_called_class()
            ));

        }

        return $transformer;

    }

}