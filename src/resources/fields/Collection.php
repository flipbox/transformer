<?php

namespace flipbox\transformer\resources\fields;

use craft\base\ElementInterface;
use Traversable;

class Collection extends \flipbox\transform\resources\Collection
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

        // Do we have one, but expect multiple
        if (!is_array($data) && !$data instanceof Traversable) {

            return [$data];

        }

        return $data;

    }

}