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

namespace Celsius3\CoreBundle\Manager;

use Doctrine\ORM\EntityManager;
use Celsius3\CoreBundle\Entity\Event\Event;
use Celsius3\CoreBundle\Entity\File;
use Celsius3\CoreBundle\Entity\Request;
use Celsius3\CoreBundle\Entity\FileDownload;
use Celsius3\CoreBundle\Entity\BaseUser;
use Symfony\Component\HttpFoundation\Request as HttpRequest;

class FileManager
{

    private $em;

    public function __construct(DocumentManager $em)
    {
        $this->em = $em;
    }

    private function countPages($file)
    {
        $im = new \Imagick();
        $im->pingImage($file);

        return $im->getNumberImages();
    }

    public function uploadFiles(Request $request, Event $event, array $files = array())
    {
        foreach ($files as $uploadedFile) {
            $file = new File();
            $file->setFile($uploadedFile);
            $file->setEvent($event);
            $file->setRequest($request);
            $file->setEnabled(true);
            $file->setPages($this->countPages($uploadedFile));
            $this->em->persist($file);
            $event->addFile($file);
        }
    }

    public function registerDownload(Request $request, File $file, HttpRequest $httpRequest, BaseUser $user)
    {
        $file->setIsDownloaded(true);
        $download = new FileDownload();
        $download->setDate(time());
        $download->setIp($httpRequest->getClientIp());
        $download->setUser($user);
        $download->setUserAgent($httpRequest->headers->get('user-agent'));
        $download->setFile($file);
        $download->setRequest($request);
        $this->em->persist($file);
        $this->em->persist($download);
        $this->em->flush();
    }

}