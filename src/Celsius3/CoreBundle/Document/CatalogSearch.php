<?php

namespace Celsius3\CoreBundle\Document;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;

/**
 * @MongoDB\Document
 */
class CatalogSearch
{
    /**
     * @MongoDB\Id
     */
    private $id;

    /**
     * @Assert\NotBlank
     * @MongoDB\String
     */
    private $result;

    /**
     * @Assert\NotNull
     * @MongoDB\Date
     */
    private $date;

    /**
     * @Assert\NotNull
     * @MongoDB\ReferenceOne(targetDocument="Catalog")
     */
    private $catalog;

    /**
     * @Assert\NotNull
     * @MongoDB\ReferenceOne(targetDocument="Order")
     */
    private $order;

    /**
     * @Assert\NotNull
     * @MongoDB\ReferenceOne(targetDocument="Instance")
     */
    private $instance;

    /**
     * @Assert\NotNull
     * @MongoDB\ReferenceOne(targetDocument="BaseUser")
     */
    private $admin;

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
     * Set result
     *
     * @param string $result
     * @return self
     */
    public function setResult($result)
    {
        $this->result = $result;
        return $this;
    }

    /**
     * Get result
     *
     * @return string $result
     */
    public function getResult()
    {
        return $this->result;
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
     * Set catalog
     *
     * @param Celsius3\CoreBundle\Document\Catalog $catalog
     * @return self
     */
    public function setCatalog(\Celsius3\CoreBundle\Document\Catalog $catalog)
    {
        $this->catalog = $catalog;
        return $this;
    }

    /**
     * Get catalog
     *
     * @return Celsius3\CoreBundle\Document\Catalog $catalog
     */
    public function getCatalog()
    {
        return $this->catalog;
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
     * Set admin
     *
     * @param Celsius3\CoreBundle\Document\BaseUser $admin
     * @return self
     */
    public function setAdmin(\Celsius3\CoreBundle\Document\BaseUser $admin)
    {
        $this->admin = $admin;
        return $this;
    }

    /**
     * Get admin
     *
     * @return Celsius3\CoreBundle\Document\BaseUser $admin
     */
    public function getAdmin()
    {
        return $this->admin;
    }
}