<?php

namespace Celsius\Celsius3Bundle\Document;

use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;

/**
 * @MongoDB\Document
 */
class StateType
{

    /**
     * @MongoDB\Id
     */
    protected $id;

    /**
     * @Assert\NotBlank()
     * @MongoDB\String
     */
    protected $name;

    /**
     * @MongoDB\ReferenceMany(targetDocument="State", mappedBy="type")
     */
    protected $states;

    public function __construct()
    {
        $this->states = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get id
     *
     * @return id $id
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return StateType
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * Get name
     *
     * @return string $name
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Add states
     *
     * @param Celsius\Celsius3Bundle\Document\State $states
     */
    public function addStates(\Celsius\Celsius3Bundle\Document\State $states)
    {
        $this->states[] = $states;
    }

    /**
     * Get states
     *
     * @return Doctrine\Common\Collections\Collection $states
     */
    public function getStates()
    {
        return $this->states;
    }

}