<?php

/**
 * @copyright  Copyright (c) Flipbox Digital Limited
 * @license    https://flipboxfactory.com/software/transformer/license
 * @link       https://www.flipboxfactory.com/software/transformer/
 */

namespace flipbox\transformer\modules\element\transformers;

use craft\base\ElementInterface;
use craft\elements\db\ElementQuery;
use flipbox\spark\helpers\QueryHelper;
use flipbox\transform\Scope;
use flipbox\transform\transformers\TransformerInterface;
use flipbox\transformer\Transformer as TransformerPlugin;
use flipbox\transformer\transformers\AbstractItemResource as BaseAbstractItemResource;

/**
 * @author Flipbox Factory <hello@flipboxfactory.com>
 * @since 1.0.0
 */
class ItemResource extends BaseAbstractItemResource
{

    /**
     * @return ElementInterface
     */
    protected function element(): ElementInterface
    {
        return ElementInterface::class;
    }

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
     * @inheritdoc
     */
    protected function resolveTransformerByHandle(string $handle): TransformerInterface
    {

        $transformer = TransformerPlugin::getInstance()->getElement()->getTransformer()->find(
            $handle,
            $this->element()
        );

        if (null === $transformer) {

            return parent::resolveTransformerByHandle($handle);

        }

        return $transformer;

    }

}