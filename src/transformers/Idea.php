<?php

namespace flipbox\transformer\transformers;

use flipbox\transform\transformers\AbstractTransformer;

class Idea extends AbstractTransformer
{

    public function transform($data)
    {

        return [
            'idea' => $data[0]
        ];

    }

}