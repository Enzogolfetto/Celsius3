<?php
/*
 * Celsius3 - Order management
 * Copyright (C) 2014 PrEBi <info@prebi.unlp.edu.ar>
 *
 * This file is part of Celsius3.
 *
 * Celsius3 is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * Celsius3 is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Celsius3.  If not, see <http://www.gnu.org/licenses/>.
 */

namespace Celsius3\CoreBundle\Tests\Document;

use Celsius3\CoreBundle\Document\MultiInstanceRequest;
use Celsius3\CoreBundle\Document\State;

class MultiInstanceRequestTest extends MultiInstanceTest
{

    protected $remoteState;

    public function setUp()
    {
        parent::setUp();

        $this->event = new MultiInstanceRequest();
        $this->remoteState = new State();
    }

    public function testGetRemoteInstance()
    {
        $this->event->setRemoteState($this->remoteState);

        $this->assertEquals($this->remoteState, $this->event->getRemoteState());
    }

    public function testGenerateMultiInstanceRequest()
    {
        $date = date('Y-m-d H:i:s');

        $this->event->setDate($date);

        $this->documentManager->persist($this->event);
        $this->documentManager->flush();

        $this->assertNotNull($this->event->getId());
    }

    public function tearDown()
    {
        parent::tearDown();

        unset($this->remoteState);
    }

}