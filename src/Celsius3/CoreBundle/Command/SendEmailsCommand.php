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
use Symfony\Component\Console\Input\InputArgument;

class SendEmailsCommand extends ContainerAwareCommand
{

    protected function configure()
    {
        $this->setName('celsius3_core:mailer:send_emails')
                ->setDescription('Send emails')
                ->addArgument('limit', InputArgument::REQUIRED, 'Limit for emails to be sent on each connection.')
                ->addArgument('log-level', InputArgument::OPTIONAL, 'Log level. 1) Max level, 2) Medium level, 3) Minimum level.', 3);
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $limit = intval($input->getArgument('limit'));
        $logLevel = intval($input->getArgument('log-level'));

        $mailer = $this->getContainer()->get('celsius3_core.mailer');
        $em = $this->getContainer()->get('doctrine.orm.entity_manager');

        $logger = $this->getContainer()->get('celsius3_core.mailer.logger');

        $instances = $em->getRepository('Celsius3CoreBundle:Instance')
                ->findAllExceptDirectory()
                ->getQuery()
                ->execute();

        foreach ($instances as $instance) {
            $output->writeln('Sending mails from instance ' . $instance->getUrl());
            $logger->info('Sending mails from instance ' . $instance->getUrl());
            $mailer->sendInstanceEmails($instance, $limit, $logger, $logLevel);
        }
    }

}
