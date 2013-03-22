<?php

namespace Celsius\Celsius3MessageBundle\Provider;
/**
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use FOS\MessageBundle\ModelManager\ThreadManagerInterface;
use FOS\MessageBundle\Security\AuthorizerInterface;
use FOS\MessageBundle\Reader\ReaderInterface;
use FOS\MessageBundle\Security\ParticipantProviderInterface;
use FOS\MessageBundle\ModelManager\MessageManagerInterface;
**/
use Celsius\Celsius3MessageBundle\ModelManager\ThreadManagerInterface;
//use Celsius\Celsius3MessageBundle\ModelManager\MessageManagerInterface;

use FOS\MessageBundle\Provider\Provider as BaseProvider;

/**
 * Provides threads for the current authenticated user
 *
 * @author Thibault Duplessis <thibault.duplessis@gmail.com>
 */

class Provider extends BaseProvider
{
    /**
     * The thread manager
     *       
     * @var ThreadManagerInterface
     */
    protected $threadManager;

    /**
     * The message manager
     *
     * @var MessageManagerInterface
     */
   // protected $messageManager;

    /**
     * The reader used to mark threads as read
     *
     * @var ReaderInterface
     */
//    protected $threadReader;

    /**
     * The authorizer manager
     *
     * @var authorizerInterface
     */
 //   protected $authorizer;

    /**
     * The participant provider instance
     *
     * @var ParticipantProviderInterface
     */
  //  protected $participantProvider;

    public function __construct(ThreadManagerInterface $threadManager, MessageManagerInterface $messageManager, ReaderInterface $threadReader, AuthorizerInterface $authorizer, ParticipantProviderInterface $participantProvider)
    {
        $this->threadManager = $threadManager;
        $this->messageManager = $messageManager;
        $this->threadReader = $threadReader;
        $this->authorizer = $authorizer;
        $this->participantProvider = $participantProvider;
    }

    /**
     * Gets the thread in the inbox of the current user
     *
     * @return array of ThreadInterface
     */
    public function getInboxThreads()
    {
        echo 'Esto pertenece a nuestro bundle!';
        $participant = $this->getAuthenticatedParticipant();
        return $this->threadManager->findParticipantInboxThreads($participant);
    }

    /**
     * Gets the thread in the sentbox of the current user
     *
     * @return array of ThreadInterface
     */
    public function getSentThreads()
    {
        echo 'Esto pertenece a nuestro bundle!';
        $participant = $this->getAuthenticatedParticipant();
        return $this->threadManager->findParticipantSentThreads($participant);
    }

    /**
     * Gets a thread by its ID
     * Performs authorization checks
     * Marks the thread as read
     *
     * @return ThreadInterface
     */
    public function getThread($threadId)
    {
        $thread = $this->threadManager->findThreadById($threadId);
        if (!$thread) {
            throw new NotFoundHttpException('There is no such thread');
        }
        if (!$this->authorizer->canSeeThread($thread)) {
            throw new AccessDeniedException('You are not allowed to see this thread');
        }
        // Load the thread messages before marking them as read
        // because we want to see the unread messages
        $thread->getMessages();
        $this->threadReader->markAsRead($thread);

        return $thread;
    }

    /**
     * Tells how many unread messages the authenticated participant has
     *
     * @return int the number of unread messages
     */
    public function getNbUnreadMessages()
    {
        return $this->messageManager->getNbUnreadMessageByParticipant($this->getAuthenticatedParticipant());
    }

    /**
     * Gets the current authenticated user
     *
     * @return ParticipantInterface
     */
    protected function getAuthenticatedParticipant()
    {
        return $this->participantProvider->getAuthenticatedParticipant();
    }
}
