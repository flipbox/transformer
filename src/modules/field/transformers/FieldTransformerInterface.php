<?php

namespace flipbox\transformer\modules\field\transformers;

use flipbox\transform\transformers\TransformerInterface;

interface FieldTransformerInterface extends TransformerInterface
{

    /**
     * @param $data
     * @return static
     */
    public function setData($data);


}