<?php

namespace flipbox\transformer\resources\fields;

use craft\base\ElementInterface;
use flipbox\spark\helpers\ArrayHelper;
use yii\db\Query;
use Traversable;

class Item extends \flipbox\transform\resources\Item
{

    /**
     * @inheritdoc
     */
    public function getData()
    {
        return $this->prepareData(
            parent::getData()
        );
    }

    /**
     * @param ElementInterface $element
     * @return mixed
     */
    protected function prepareData(ElementInterface $element)
    {

        $data = $element->getFieldValue(
            $this->getIdentifier()
        );

        // Do we have multiple, but expect one
        if (is_array($data) || $data instanceof Traversable) {

            if ($data instanceof Query) {
                return $data->one();
            }

            return ArrayHelper::firstValue($data);

        }

        return $data;

    }

}