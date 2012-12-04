<?php
// src/Acme/MessageBundle/Document/Thread.php
namespace Celsius\Celsius3Bundle\Document;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use FOS\MessageBundle\Document\Thread as BaseThread;

/**
 * @MongoDB\Document
 */
class Thread extends BaseThread
{
    /**
     * @MongoDB\Id
     */
    protected $id;

    /**
     * @MongoDB\ReferenceMany(targetDocument="Celsius\Celsius3Bundle\Document\Message")
     */
    protected $messages;

    /**                                     
     * @MongoDB\EmbedMany(targetDocument="Celsius\Celsius3Bundle\Document\ThreadMetadata")
     */
    protected $metadata;

    /**
     * @MongoDB\ReferenceMany(targetDocument="Celsius\Celsius3Bundle\Document\BaseUser")
     */
    protected $participants;

    /**
     * @MongoDB\ReferenceOne(targetDocument="Celsius\Celsius3Bundle\Document\BaseUser")
     */
    protected $createdBy;
}
?>