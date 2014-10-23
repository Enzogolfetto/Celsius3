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

namespace Celsius3\CoreBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Celsius3\CoreBundle\Services\Analytics;

class AnalyticsCommand extends ContainerAwareCommand
{

    protected function configure()
    {
        $this->setName('core:analytics')
            ->setDescription('');
    }
    
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        /*$an = $this->getContainer()->get('celsius3_core.statistic_manager')->calculateOrdersCounters();
        $output->writeln($an);*/
        
        $users = $this->getContainer()->get('celsius3_core.statistic_manager')->calculateUsersAnalytics();
        $output->writeln($users);
    }

}