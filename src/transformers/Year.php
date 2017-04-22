<?php

namespace flipbox\transformer\transformers;

use flipbox\transform\transformers\AbstractTransformer;

class Year extends AbstractTransformer
{

    public function transform($data)
    {

        return [
            'year' => 1999
        ];

    }

}