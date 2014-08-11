<?php

namespace Celsius3\CoreBundle\Document\Event;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;
use Celsius3\CoreBundle\Helper\LifecycleHelper;
use Celsius3\CoreBundle\Document\Mixin\ReclaimableTrait;
use Celsius3\CoreBundle\Document\Mixin\CancellableTrait;
use Celsius3\CoreBundle\Document\Mixin\ProviderTrait;
use Celsius3\CoreBundle\Document\Request;

/**
 * @ODM\Document
 */
class SingleInstanceRequestEvent extends SingleInstanceEvent
{
    use ReclaimableTrait,
        CancellableTrait,
        ProviderTrait;

    public function applyExtraData(Request $request, array $data, LifecycleHelper $lifecycleHelper, $date)
    {
        $this->setProvider($data['extraData']['provider']);
        $this->setObservations($data['extraData']['observations']);
    }
}