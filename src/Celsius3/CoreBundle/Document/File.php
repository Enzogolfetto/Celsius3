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

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;
use Doctrine\MongoDB\GridFSFile;
use Gedmo\Timestampable\Traits\TimestampableDocument;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ODM\Document
 * @ODM\HasLifecycleCallbacks
 * @ODM\Indexes({
 *   @ODM\Index(keys={"instance.id"="asc", "request.id"="asc"}),
 * })
 */
class File
{
    use TimestampableDocument;
    /**
     * @ODM\Id
     */
    private $id;
    /**
     * @ODM\String
     */
    private $name;
    /**
     * @ODM\String
     */
    private $path;
    /**
     * @ODM\String
     */
    private $comments;
    /**
     * @ODM\String
     */
    private $mime;
    /**
     * @ODM\File
     */
    private $file;
    /**
     * @ODM\Date
     */
    private $uploaded;
    /**
     * @ODM\Boolean
     */
    private $enabled;
    /**
     * @ODM\ReferenceOne(targetDocument="Request", inversedBy="files")
     */
    private $request;
    /**
     * @ODM\ReferenceOne(targetDocument="Instance", inversedBy="files")
     */
    private $instance;
    /**
     * @ODM\ReferenceOne(targetDocument="Celsius3\CoreBundle\Document\Event\Event", inversedBy="files")
     */
    private $event;
    /**
     * @ODM\Boolean
     */
    private $isDownloaded = false;
    /**
     * @ODM\Int
     */
    private $pages = 0;

    public function getUploadDir()
    {
        $class = new \ReflectionClass($this);
        return dirname($class->getFileName()) . DIRECTORY_SEPARATOR . '..' .
                DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . '..' .
                DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'web' .
                DIRECTORY_SEPARATOR . 'uploads' . DIRECTORY_SEPARATOR . 'temp';
    }

    /**
     * @ODM\PrePersist()
     * @ODM\PreUpdate()
     */
    public function prePersist()
    {
        if (!($this->file instanceof GridFSFile)) {
            $this->setName($this->file->getClientOriginalName());
            $this->setMime($this->file->getMimeType());
            $this->setPath(md5(rand(0, 999999)) . '.' . $this->getFile()->guessExtension());
            $this->getFile()->move($this->getUploadDir(), $this->getPath());
            $this->setFile($this->getUploadDir() . DIRECTORY_SEPARATOR . $this->getPath());
            $this->setUploaded(date('Y-m-d H:i:s'));
        }
    }

    /**
     * @ODM\PostPersist()
     * @ODM\PostUpdate()
     */
    public function postPersist()
    {
        if (file_exists($this->getUploadDir() . '/' . $this->getPath())) {
            unlink($this->getUploadDir() . '/' . $this->getPath());
        }
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
     * Set path
     *
     * @param  string $path
     * @return self
     */
    public function setPath($path)
    {
        $this->path = $path;

        return $this;
    }

    /**
     * Get path
     *
     * @return string $path
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * Set comments
     *
     * @param  string $comments
     * @return self
     */
    public function setComments($comments)
    {
        $this->comments = $comments;

        return $this;
    }

    /**
     * Get comments
     *
     * @return string $comments
     */
    public function getComments()
    {
        return $this->comments;
    }

    /**
     * Set mime
     *
     * @param  string $mime
     * @return self
     */
    public function setMime($mime)
    {
        $this->mime = $mime;

        return $this;
    }

    /**
     * Get mime
     *
     * @return string $mime
     */
    public function getMime()
    {
        return $this->mime;
    }

    /**
     * Set file
     *
     * @param  file $file
     * @return self
     */
    public function setFile($file)
    {
        $this->file = $file;

        return $this;
    }

    /**
     * Get file
     *
     * @return file $file
     */
    public function getFile()
    {
        return $this->file;
    }

    /**
     * Set uploaded
     *
     * @param  date $uploaded
     * @return self
     */
    public function setUploaded($uploaded)
    {
        $this->uploaded = $uploaded;

        return $this;
    }

    /**
     * Get uploaded
     *
     * @return date $uploaded
     */
    public function getUploaded()
    {
        return $this->uploaded;
    }

    /**
     * Set enabled
     *
     * @param  boolean $enabled
     * @return self
     */
    public function setEnabled($enabled)
    {
        $this->enabled = $enabled;

        return $this;
    }

    /**
     * Get enabled
     *
     * @return boolean $enabled
     */
    public function getEnabled()
    {
        return $this->enabled;
    }

    /**
     * Set event
     *
     * @param  Celsius3\CoreBundle\Document\Event\Event $event
     * @return self
     */
    public function setEvent(\Celsius3\CoreBundle\Document\Event\Event $event = null)
    {
        $this->event = $event;

        return $this;
    }

    /**
     * Get event
     *
     * @return Celsius3\CoreBundle\Document\Event\Event $event
     */
    public function getEvent()
    {
        return $this->event;
    }

    /**
     * Set isDownloaded
     *
     * @param  boolean $isDownloaded
     * @return self
     */
    public function setIsDownloaded($isDownloaded)
    {
        $this->isDownloaded = $isDownloaded;

        return $this;
    }

    /**
     * Get isDownloaded
     *
     * @return boolean $isDownloaded
     */
    public function getIsDownloaded()
    {
        return $this->isDownloaded;
    }

    /**
     * Set request
     *
     * @param  Celsius3\CoreBundle\Document\Request $request
     * @return self
     */
    public function setRequest(\Celsius3\CoreBundle\Document\Request $request = null)
    {
        $this->request = $request;

        return $this;
    }

    /**
     * Get request
     *
     * @return Celsius3\CoreBundle\Document\Request $request
     */
    public function getRequest()
    {
        return $this->request;
    }

    /**
     * Set pages
     *
     * @param  integer $pages
     * @return self
     */
    public function setPages($pages)
    {
        $this->pages = $pages;

        return $this;
    }

    /**
     * Get pages
     *
     * @return integer $pages
     */
    public function getPages()
    {
        return $this->pages;
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
}
