<?php

namespace Celsius\Celsius3Bundle\Document;
use Celsius\Celsius3Bundle\Helper\LifecycleHelper;

interface EventInterface
{
    public function applyExtraData(Order $order, array $data,
            LifecycleHelper $lifecycleHelper, $date);
}
