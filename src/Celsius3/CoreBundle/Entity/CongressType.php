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

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 */
class CongressType extends MaterialType
{
    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $place;
    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $communication;

    /**
     * Set place
     *
     * @param  string $place
     * @return self
     */
    public function setPlace($place)
    {
        $this->place = $place;

        return $this;
    }

    /**
     * Get place
     *
     * @return string $place
     */
    public function getPlace()
    {
        return $this->place;
    }

    /**
     * Set communication
     *
     * @param  string $communication
     * @return self
     */
    public function setCommunication($communication)
    {
        $this->communication = $communication;

        return $this;
    }

    /**
     * Get communication
     *
     * @return string $communication
     */
    public function getCommunication()
    {
        return $this->communication;
    }
}