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

namespace Celsius3\CoreBundle\Entity;

use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity
 * @ORM\Table(name="catalog_result")
 */
class CatalogResult
{

    use TimestampableEntity;
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
    /**
     * @Assert\NotBlank
     * @Assert\NotNull
     * @ORM\Column(type="string", length=255)
     */
    private $title;
    /**
     * @Assert\Type(type="integer")
     * @ORM\Column(type="integer")
     */
    private $searches = 0;
    /**
     * @Assert\Type(type="integer")
     * @ORM\Column(type="integer")
     */
    private $matches = 0;
    /**
     * @Assert\NotNull
     * @ORM\ManyToOne(targetEntity="Catalog", inversedBy="positions")
     * @ORM\JoinColumn(name="catalog_id", referencedColumnName="id", nullable=false)
     */
    private $catalog;

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
     * Set title
     *
     * @param  string $title
     * @return self
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string $title
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set searches
     *
     * @param  int  $searches
     * @return self
     */
    public function setSearches($searches)
    {
        $this->searches = $searches;

        return $this;
    }

    /**
     * Get searches
     *
     * @return int $searches
     */
    public function getSearches()
    {
        return $this->searches;
    }

    /**
     * Set matches
     *
     * @param  int  $matches
     * @return self
     */
    public function setMatches($matches)
    {
        $this->matches = $matches;

        return $this;
    }

    /**
     * Get matches
     *
     * @return int $matches
     */
    public function getMatches()
    {
        return $this->matches;
    }

    /**
     * Set catalog
     *
     * @param  Celsius3\CoreBundle\Entity\Catalog $catalog
     * @return self
     */
    public function setCatalog(\Celsius3\CoreBundle\Entity\Catalog $catalog)
    {
        $this->catalog = $catalog;

        return $this;
    }

    /**
     * Get catalog
     *
     * @return Celsius3\CoreBundle\Entity\Catalog $catalog
     */
    public function getCatalog()
    {
        return $this->catalog;
    }
}
