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
use Celsius3\CoreBundle\Entity\Instance;

class FileManager
{

    const LOGOS_UPLOAD_DIR = '/Users/agustin/php-workspace/Celsius3/web/uploads/logos';

    private $em;

    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    private function countPages($file)
    {
        exec("exiftool " . $file->getRealPath() . " | awk '/Page Count/ { print $4 }'", $output);
        return $output[0];
    }

    public function uploadFiles(Request $request, Event $event, array $files = array())
    {
        foreach ($files as $uploadedFile) {
            $file = new File();
            $file->setName($uploadedFile->getClientOriginalName());
            $file->setFile($uploadedFile);
            $file->setEvent($event);
            $file->setRequest($request);
            $file->setEnabled(true);
            $file->setPages($this->countPages($uploadedFile));
            $file->setInstance($request->getInstance());
            $this->em->persist($file);
            $event->addFile($file);
            $this->em->persist($event);
        }
    }

    public function registerDownload(Request $request, File $file, HttpRequest $httpRequest, BaseUser $user)
    {
        if (!$user->hasRole('ROLE_ADMIN') && !$user->hasRole('ROLE_SUPER_ADMIN')) {
            $file->setIsDownloaded(true);
        }

        $download = new FileDownload();
        $download->setIp($httpRequest->getClientIp());
        $download->setUser($user);
        $download->setUserAgent($httpRequest->headers->get('user-agent'));
        $download->setFile($file);
        $download->setRequest($request);
        $download->setInstance($user->getInstance());
        $this->em->persist($file);
        $this->em->persist($download);
        $this->em->flush();
    }

    public function copyFilesToPreviousRequest(Request $previousRequest, Request $request, Event $event, Instance $instance = null)
    {
        foreach ($request->getFiles() as $f) {
            $file = clone $f;
            $file->setInstance($instance);
            $file->setRequest($previousRequest);
            $file->setEvent($event);
            if (!copy($f->getUploadRootDir() . DIRECTORY_SEPARATOR . $f->getPath(), $file->getUploadRootDir() . DIRECTORY_SEPARATOR . $file->getPath())) {
                throw new Exception('Copy file error');
            }
            $this->em->persist($file);
        }

        $this->em->flush();
    }

}
