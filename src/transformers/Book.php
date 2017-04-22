<?php

namespace flipbox\transformer\transformers;

use craft\base\Plugin as BasePlugin;
use flipbox\transform\Collection;
use flipbox\transform\Factory;
use flipbox\transform\transformers\AbstractTransformer;

class Book extends AbstractTransformer
{

    public $defaultIncludes = [
        'year',
        'ideas'
    ];

    public function transform($data)
    {

        return [
          'name' => $data['title']
        ];

    }

    public function includeYear($data)
    {
        return $this->item($data, new Year());
    }

    public function includeIdeas($data)
    {

        $ideas = [['new'], ['big'], ['idea']];
        return $this->collection($ideas, new Idea());
    }

}