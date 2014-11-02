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

namespace Celsius3\CoreBundle\Entity\Mixin;

trait ProviderTrait
{
    /**
     * @Assert\NotNull(groups={"request"})
     * @ORM\ManyToOne(targetEntity="Celsius3\CoreBundle\Entity\Provider")
     * @ORM\JoinColumn(name="provider_id", referencedColumnName="id", nullable=false)
     */
    private $provider;

    /**
     * Set provider
     *
     * @param  Celsius3\CoreBundle\Entity\Provider $provider
     * @return \SingleInstanceRequest
     */
    public function setProvider(\Celsius3\CoreBundle\Entity\Provider $provider)
    {
        $this->provider = $provider;

        return $this;
    }

    /**
     * Get provider
     *
     * @return Celsius3\CoreBundle\Entity\Provider $provider
     */
    public function getProvider()
    {
        return $this->provider;
    }
}