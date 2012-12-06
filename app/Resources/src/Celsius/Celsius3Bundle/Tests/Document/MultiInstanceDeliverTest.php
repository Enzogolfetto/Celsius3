<?php

namespace Celsius\Celsius3Bundle\Tests\Document;

use Celsius\Celsius3Bundle\Document\MultiInstanceDeliver;

class MultiInstanceDeliverTest extends MultiInstanceTest
{

    public function setUp()
    {
        parent::setUp();

        $this->event = new MultiInstanceDeliver();
    }
    
    public function testGenerateMultiInstanceDeliver()
    {
        $date = date('Y-m-d H:i:s');

        $this->event->setDate($date);

        $this->documentManager->persist($this->event);
        $this->documentManager->flush();

        $this->assertNotNull($this->event->getId());
    }

}
