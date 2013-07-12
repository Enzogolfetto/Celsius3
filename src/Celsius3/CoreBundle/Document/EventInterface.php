<?php

namespace Celsius3\CoreBundle\Document;
use Celsius3\CoreBundle\Helper\LifecycleHelper;

interface EventInterface
{
    public function applyExtraData(Order $order, array $data,
            LifecycleHelper $lifecycleHelper, $date);
}