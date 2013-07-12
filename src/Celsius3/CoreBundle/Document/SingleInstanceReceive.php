<?php

namespace Celsius3\CoreBundle\Document;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use Celsius3\CoreBundle\Helper\LifecycleHelper;
use Celsius3\CoreBundle\Document\Mixin\ReclaimableTrait;

/**
 * @MongoDB\Document
 */
class SingleInstanceReceive extends SingleInstance
{
    use ReclaimableTrait;

    /**
     * @Assert\NotBlank
     * @MongoDB\String
     */
    private $deliveryType;

    /**
     * @MongoDB\ReferenceMany(targetDocument="File", mappedBy="event", cascade={"persist"})
     */
    private $files;

    /**
     * @Assert\NotNull
     * @MongoDB\ReferenceOne(targetDocument="Event")
     */
    private $requestEvent;

    public function applyExtraData(Order $order, array $data,
            LifecycleHelper $lifecycleHelper, $date)
    {
        $this->setRequestEvent($data['extraData']['request']);
        $this->setObservations($data['extraData']['observations']);
        $lifecycleHelper
                ->uploadFiles($order, $this, $data['extraData']['files']);
    }

    public function __construct()
    {
        $this->files = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Set deliveryType
     *
     * @param string $deliveryType
     * @return self
     */
    public function setDeliveryType($deliveryType)
    {
        $this->deliveryType = $deliveryType;
        return $this;
    }

    /**
     * Get deliveryType
     *
     * @return string $deliveryType
     */
    public function getDeliveryType()
    {
        return $this->deliveryType;
    }

    /**
     * Add files
     *
     * @param Celsius3\CoreBundle\Document\File $files
     */
    public function addFile(\Celsius3\CoreBundle\Document\File $files)
    {
        $this->files[] = $files;
    }

    /**
     * Remove files
     *
     * @param Celsius3\CoreBundle\Document\File $files
     */
    public function removeFile(\Celsius3\CoreBundle\Document\File $files)
    {
        $this->files->removeElement($files);
    }

    /**
     * Get files
     *
     * @return Doctrine\Common\Collections\Collection $files
     */
    public function getFiles()
    {
        return $this->files;
    }

    /**
     * Set requestEvent
     *
     * @param Celsius3\CoreBundle\Document\Event $requestEvent
     * @return self
     */
    public function setRequestEvent(\Celsius3\CoreBundle\Document\Event $requestEvent)
    {
        $this->requestEvent = $requestEvent;
        return $this;
    }

    /**
     * Get requestEvent
     *
     * @return Celsius3\CoreBundle\Document\Event $requestEvent
     */
    public function getRequestEvent()
    {
        return $this->requestEvent;
    }
}