<?php

namespace Celsius\Celsius3Bundle\Tests\Document;

use Celsius\Celsius3Bundle\Tests\TestCase;
use Celsius\Celsius3Bundle\Document\StateType;
use Celsius\Celsius3Bundle\Document\State;

class StateTypeTest extends TestCase
{

    protected $stateType;
    protected $state;

    public function setUp()
    {
        parent::setUp();

        $this->stateType = new StateType();
        $this->state = new State();
    }

    public function testGetName()
    {
        $name = 'Sample name';

        $this->stateType->setName($name);

        $this->assertEquals($name, $this->stateType->getName());
    }

    public function testGetStates()
    {
        $this->stateType->addStates($this->state);

        $this->assertContains($this->state, $this->stateType->getStates());
    }

    public function testGenerateState()
    {
        $name = 'Sample name';

        $this->stateType->setName($name);

        $this->assertNull($this->stateType->getId());

        $this->documentManager->persist($this->stateType);
        $this->documentManager->flush();

        $this->assertNotNull($this->stateType->getId());
    }

    public function tearDown()
    {
        parent::tearDown();

        unset($this->stateType, $this->state);
    }

}