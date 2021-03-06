<?php

/*
 * Celsius3 - Order management
 * Copyright (C) 2014 PREBI-SEDICI <info@prebi.unlp.edu.ar> http://prebi.unlp.edu.ar http://sedici.unlp.edu.ar
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

namespace Celsius3\CoreBundle\Entity\Event;

use Celsius3\CoreBundle\Entity\Mixin\CancellableTrait;
use Celsius3\CoreBundle\Entity\Mixin\ProviderTrait;
use Celsius3\CoreBundle\Entity\Mixin\ReclaimableTrait;
use Celsius3\CoreBundle\Entity\Request;
use Celsius3\CoreBundle\Helper\LifecycleHelper;
use Celsius3\NotificationBundle\Entity\Notifiable;
use Celsius3\NotificationBundle\Manager\NotificationManager;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="Celsius3\CoreBundle\Repository\BaseRepository")
 */
class SingleInstanceRequestEvent extends SingleInstanceEvent implements Notifiable
{
    use ReclaimableTrait,
        CancellableTrait,
        ProviderTrait;

    public function getEventType()
    {
        return 'sirequest';
    }

    public function applyExtraData(Request $request, array $data, LifecycleHelper $lifecycleHelper, $date)
    {
        $this->setProvider($data['extraData']['provider']);
        $this->setObservations($data['extraData']['observations']);
    }

    public function notify(NotificationManager $manager)
    {
        $manager->notifyEvent($this, 'request');
    }

    /**
     * Get reclaimed.
     *
     * @return bool
     */
    public function getReclaimed()
    {
        return $this->reclaimed;
    }

    /**
     * Get cancelled.
     *
     * @return bool
     */
    public function getCancelled()
    {
        return $this->cancelled;
    }
}
