<?php

namespace flipbox\transformer\transformers;

use craft\base\Plugin as BasePlugin;
use craft\elements\Entry;
use flipbox\transform\Collection;
use flipbox\transform\Factory;
use flipbox\transform\transformers\AbstractTransformer;
use flipbox\transform\transformers\ItemInterface;
use flipbox\transform\transformers\TransformerInterface;

class Date extends AbstractTransformer
{

    public $format = 'Y';

    /**
     * @param \DateTime $dateTime
     * @return array
     */
    public function transform(\DateTime $dateTime)
    {

        return [
            'utc' => $dateTime->format('c'),
            'formatted' => $dateTime->format($this->getFormat())
        ];

    }

    /**
     * @return string
     */
    private function getFormat()
    {

        if($format = $this->getParams()->get('format')) {

            return reset($format);

        }

        return $this->format;
    }

}