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

namespace Celsius3\CoreBundle\Document;

use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;
use Gedmo\Timestampable\Traits\TimestampableDocument;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ODM\Document
 * @ODM\Indexes({
 *   @ODM\UniqueIndex(keys={"key"="asc", "instance.id"="asc"}),
 *   @ODM\Index(keys={"instance.id"="asc", "key"="asc"}),
 * })
 */
class CustomUserField
{
    use TimestampableDocument;
    /**
     * @ODM\Id
     */
    private $id;
    /**
     * @Gedmo\Slug(fields={"name"})
     * @ODM\String
     */
    private $key;
    /**
     * @Assert\NotBlank()
     * @ODM\String
     */
    private $name;
    /**
     * @Assert\Type(type="boolean")
     * @ODM\Boolean
     */
    private $private = true;
    /**
     * @Assert\Type(type="boolean")
     * @ODM\Boolean
     */
    private $required = true;
    /**
     * @ODM\ReferenceOne(targetDocument="Instance")
     */
    private $instance;
    /**
     * @ODM\ReferenceMany(targetDocument="CustomUserValue", mappedBy="field")
     */
    private $values;

    public function __construct()
    {
        $this->values = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set key
     *
     * @param  string $key
     * @return self
     */
    public function setKey($key)
    {
        $this->key = $key;

        return $this;
    }

    /**
     * Get key
     *
     * @return string $key
     */
    public function getKey()
    {
        return $this->key;
    }

    /**
     * Set name
     *
     * @param  string $name
     * @return self
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
     * Set private
     *
     * @param  boolean $private
     * @return self
     */
    public function setPrivate($private)
    {
        $this->private = $private;

        return $this;
    }

    /**
     * Get private
     *
     * @return boolean $private
     */
    public function getPrivate()
    {
        return $this->private;
    }

    /**
     * Set required
     *
     * @param  boolean $required
     * @return self
     */
    public function setRequired($required)
    {
        $this->required = $required;

        return $this;
    }

    /**
     * Get required
     *
     * @return boolean $required
     */
    public function getRequired()
    {
        return $this->required;
    }

    /**
     * Set instance
     *
     * @param  Celsius3\CoreBundle\Document\Instance $instance
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
     * Add values
     *
     * @param Celsius3\CoreBundle\Document\CustomUserValue $values
     */
    public function addValue(\Celsius3\CoreBundle\Document\CustomUserValue $values)
    {
        $this->values[] = $values;
    }

    /**
     * Remove values
     *
     * @param Celsius3\CoreBundle\Document\CustomUserValue $values
     */
    public function removeValue(\Celsius3\CoreBundle\Document\CustomUserValue $values)
    {
        $this->values->removeElement($values);
    }

    /**
     * Get values
     *
     * @return Doctrine\Common\Collections\Collection $values
     */
    public function getValues()
    {
        return $this->values;
    }
}