<?php

namespace flipbox\transformer\modules\element\transformers;

use craft\base\ElementInterface;
use craft\elements\db\ElementQuery;
use craft\helpers\ArrayHelper;
use flipbox\spark\helpers\ObjectHelper;
use flipbox\spark\helpers\QueryHelper;
use flipbox\transform\Scope;
use flipbox\transform\transformers\TransformerInterface;
use flipbox\transformer\Plugin;
use flipbox\transformer\transformers\traits\ResolveTransformer;
use Traversable;
use flipbox\transform\transformers\Item as ItemResource;
use yii\base\Exception;

abstract class AbstractItemResource extends ItemResource
{

    /**
     * @return ElementInterface
     */
    protected abstract function element(): ElementInterface;

    /**
     * @inheritdoc
     */
    public function __construct(array $config = [])
    {

        // Ensure we have a valid transformer
        if ($transformer = ArrayHelper::remove($config, 'transformer', 'default')) {
            $config['transformer'] = $this->resolveTransformer($transformer);
        }

        parent::__construct($config);

    }

    /**
     * @inheritdoc
     */
    protected function getData(Scope $scope)
    {

        /** @var ElementQuery $query */
        $query = parent::getData($scope);

        if($queryParams = $this->getQueryParams($scope)) {
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

        foreach($this->queryParams() as $param) {

            if($value = $paramBag->get($param)) {

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
     * @param $transformer
     * @return TransformerInterface|callable
     */
    protected function resolveTransformer($transformer)
    {

        if(is_callable($transformer) || $transformer instanceof TransformerInterface) {

            return $transformer;

        }

        if (is_string($transformer)) {

            if (is_subclass_of($transformer, TransformerInterface::class)) {

                return $this->resolveTransformerByClass($transformer);

            }

            return $this->resolveTransformerByHandle($transformer);

        }

        // todo log this

        return function(){

            // empty callable

        };

    }

    /**
     * @param $class
     * @return TransformerInterface
     */
    protected function resolveTransformerByClass($class): TransformerInterface
    {
        return new $class();
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

        if(null === $transformer) {

            throw new Exception(sprintf(
                "Transformer '%s' does not exist on resource '%s'.",
                (string) $handle,
                (string) get_called_class()
            ));

        }

        return $transformer;

    }

}