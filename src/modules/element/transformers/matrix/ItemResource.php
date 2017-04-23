<?php

namespace flipbox\transformer\modules\element\transformers\matrix;

use craft\base\ElementInterface;
use craft\elements\MatrixBlock;
use flipbox\transformer\modules\element\transformers\AbstractItemResource;

class ItemResource extends AbstractItemResource
{

    /**
     * @return ElementInterface
     */
    protected function element(): ElementInterface
    {
        return new MatrixBlock();
    }

}
