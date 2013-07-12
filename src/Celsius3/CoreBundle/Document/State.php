<?php

namespace Celsius3\CoreBundle\Document;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;

/**
 * @MongoDB\Document
 * @MongoDB\Document(repositoryClass="Celsius3\CoreBundle\Repository\StateRepository")
 */
class State
{

    /**
     * @MongoDB\Id
     */
    private $id;

    /**
     * @Assert\NotBlank
     * @Assert\Date
     * @MongoDB\Date
     */
    private $date;

    /**
     * @Assert\NotBlank
     * @Assert\Type(type="bool")
     * @MongoDB\Boolean
     */
    private $isCurrent = true;

    /**
     * @Assert\NotNull
     * @MongoDB\ReferenceOne(targetDocument="StateType", inversedBy="states")
     */
    private $type;

    /**
     * @MongoDB\ReferenceOne
     */
    private $remoteEvent;

    /**
     * @Assert\NotNull
     * @MongoDB\ReferenceOne(targetDocument="Instance", inversedBy="states")
     */
    private $instance;

    /**
     * @MongoDB\ReferenceMany(targetDocument="Event", mappedBy="state")
     */
    private $events;

    /**
     * @MongoDB\ReferenceMany(targetDocument="MultiInstanceReceive", mappedBy="remoteState")
     */
    private $remoteEvents;

    /**
     * @MongoDB\ReferenceOne(targetDocument="State")
     */
    private $previous;

    /**
     * @Assert\NotNull
     * @MongoDB\ReferenceOne(targetDocument="Order", inversedBy="states")
     */
    private $order;

    public function __construct()
    {
        $this->events = new \Doctrine\Common\Collections\ArrayCollection();
    }

    public function __toString()
    {
        return $this->getType()->__toString();
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
     * Set date
     *
     * @param date $date
     * @return self
     */
    public function setDate($date)
    {
        $this->date = $date;
        return $this;
    }

    /**
     * Get date
     *
     * @return date $date
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Set isCurrent
     *
     * @param boolean $isCurrent
     * @return self
     */
    public function setIsCurrent($isCurrent)
    {
        $this->isCurrent = $isCurrent;
        return $this;
    }

    /**
     * Get isCurrent
     *
     * @return boolean $isCurrent
     */
    public function getIsCurrent()
    {
        return $this->isCurrent;
    }

    /**
     * Set type
     *
     * @param Celsius3\CoreBundle\Document\StateType $type
     * @return self
     */
    public function setType(\Celsius3\CoreBundle\Document\StateType $type)
    {
        $this->type = $type;
        return $this;
    }

    /**
     * Get type
     *
     * @return Celsius3\CoreBundle\Document\StateType $type
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set remoteEvent
     *
     * @param $remoteEvent
     * @return self
     */
    public function setRemoteEvent($remoteEvent)
    {
        $this->remoteEvent = $remoteEvent;
        return $this;
    }

    /**
     * Get remoteEvent
     *
     * @return $remoteEvent
     */
    public function getRemoteEvent()
    {
        return $this->remoteEvent;
    }

    /**
     * Set instance
     *
     * @param Celsius3\CoreBundle\Document\Instance $instance
     * @return self
     */
    public function setInstance(\Celsius3\CoreBundle\Document\Instance $instance)
    {
        $this->instance = $instance;
        return $this;
    }

    /**
     * Get instance
     *
     * @return Celsius3\CoreBundle\Document\Instance $instance
     */
    public function getInstance()
    {
        return $this->instance;
    }

    /**
     * Add events
     *
     * @param Celsius3\CoreBundle\Document\Event $events
     */
    public function addEvent(\Celsius3\CoreBundle\Document\Event $events)
    {
        $this->events[] = $events;
    }

    /**
     * Remove events
     *
     * @param Celsius3\CoreBundle\Document\Event $events
     */
    public function removeEvent(\Celsius3\CoreBundle\Document\Event $events)
    {
        $this->events->removeElement($events);
    }

    /**
     * Get events
     *
     * @return Doctrine\Common\Collections\Collection $events
     */
    public function getEvents()
    {
        return $this->events;
    }

    /**
     * Add remoteEvents
     *
     * @param Celsius3\CoreBundle\Document\MultiInstanceReceive $remoteEvents
     */
    public function addRemoteEvent(\Celsius3\CoreBundle\Document\MultiInstanceReceive $remoteEvents)
    {
        $this->remoteEvents[] = $remoteEvents;
    }

    /**
     * Remove remoteEvents
     *
     * @param Celsius3\CoreBundle\Document\MultiInstanceReceive $remoteEvents
     */
    public function removeRemoteEvent(\Celsius3\CoreBundle\Document\MultiInstanceReceive $remoteEvents)
    {
        $this->remoteEvents->removeElement($remoteEvents);
    }

    /**
     * Get remoteEvents
     *
     * @return Doctrine\Common\Collections\Collection $remoteEvents
     */
    public function getRemoteEvents()
    {
        return $this->remoteEvents;
    }

    /**
     * Set previous
     *
     * @param Celsius3\CoreBundle\Document\State $previous
     * @return self
     */
    public function setPrevious(\Celsius3\CoreBundle\Document\State $previous = null)
    {
        $this->previous = $previous;
        return $this;
    }

    /**
     * Get previous
     *
     * @return Celsius3\CoreBundle\Document\State $previous
     */
    public function getPrevious()
    {
        return $this->previous;
    }

    /**
     * Set order
     *
     * @param Celsius3\CoreBundle\Document\Order $order
     * @return self
     */
    public function setOrder(\Celsius3\CoreBundle\Document\Order $order)
    {
        $this->order = $order;
        return $this;
    }

    /**
     * Get order
     *
     * @return Celsius3\CoreBundle\Document\Order $order
     */
    public function getOrder()
    {
        return $this->order;
    }
}