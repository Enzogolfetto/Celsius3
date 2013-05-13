<?php

namespace Celsius\Celsius3Bundle\Helper;
use Doctrine\ODM\MongoDB\DocumentManager;
use Celsius\Celsius3Bundle\Document\Approve;
use Celsius\Celsius3Bundle\Document\Order;
use Celsius\Celsius3Bundle\Document\State;
use Celsius\Celsius3Bundle\Document\Event;
use Celsius\Celsius3Bundle\Document\MultiInstanceRequest;
use Celsius\Celsius3Bundle\Document\SingleInstanceRequest;
use Celsius\Celsius3Bundle\Document\Instance;
use Celsius\Celsius3Bundle\Document\MultiInstanceReceive;
use Celsius\Celsius3Bundle\Document\SingleInstanceReceive;
use Celsius\Celsius3Bundle\Helper\InstanceHelper;
use Celsius\Celsius3Bundle\Manager\StateManager;
use Celsius\Celsius3Bundle\Manager\EventManager;
use Celsius\Celsius3Bundle\Manager\FileManager;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Celsius\Celsius3Bundle\Exception\PreviousStateNotFoundException;

class LifecycleHelper
{
    private $dm;
    private $state_manager;
    private $event_manager;
    private $file_manager;
    private $instance_helper;
    private $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
        $this->dm = $this->container
                ->get('doctrine.odm.mongodb.document_manager');
        $this->state_manager = $this->container->get('state_manager');
        $this->event_manager = $this->container->get('event_manager');
        $this->file_manager = $this->container->get('file_manager');
        $this->instance_helper = $this->container->get('instance_helper');
    }

    public function uploadFiles(Order $order, Event $event, array $files)
    {
        $this->file_manager->uploadFiles($order, $event, $files);
    }

    private function setEventData(Order $order, array $data)
    {
        $event = new $data['eventClassName'];
        $event->setDate($data['date']);
        $event->setOperator($order->getOperator());
        $event->setInstance($data['instance']);
        $event->setOrder($order);
        $event->setState($this->getState($order, $event, $data));
        $event
                ->applyExtraData($order, $data['extraData'], $this,
                        $data['date']);
        $this->dm->persist($event);
    }

    public function getState(Order $order, Event $event, array $data,
            Event $remoteEvent = null)
    {
        $currentState = $order->getCurrentState($data['instance']);

        $instance = is_null($data['instance']) ? $order->getInstance()
                : $data['instance'];

        if ($order->hasState($data['stateName'], $instance)) {
            $state = $order->getState($data['stateName'], $instance);
            $state->setRemoteEvent($remoteEvent);
        } else {
            if (!is_null($currentState)) {
                $currentState->setIsCurrent(false);
                $this->dm->persist($currentState);
            }
            $state = $this
                    ->createState($order, $instance, $data, $currentState,
                            $remoteEvent);
        }
        $state->addEvents($event);

        return $state;
    }

    private function createState(Order $order, Instance $instance, array $data,
            State $currentState = null, Event $remoteEvent = null)
    {
        $state = new State();
        $state->setDate($data['date']);
        $state->setInstance($instance);
        $state->setOrder($order);
        $state
                ->setType(
                        $this->dm
                                ->getRepository(
                                        'CelsiusCelsius3Bundle:StateType')
                                ->findOneBy(array('name' => $data['stateName'])));
        $state->setPrevious($currentState);
        $state->setRemoteEvent($remoteEvent);

        return $state;
    }

    private function preValidate($name, Order $order)
    {
        $extraData = $this->event_manager->prepareExtraData($name, $order);
        $data = array(
                'eventName' => $this->event_manager
                        ->getRealEventName($name, $extraData),
                'stateName' => $this->state_manager->getStateForEvent($name),
                'instance' => $name != EventManager::EVENT__CREATION ? $this
                                ->instance_helper->getSessionInstance()
                        : $order->getInstance(), 'date' => date('Y-m-d H:i:s'),
                'extraData' => $extraData,
                'orderDateMethod' => 'set'
                        . ucfirst($this->state_manager->getStateForEvent($name)),
                'eventClassName' => $this->event_manager
                        ->getFullClassNameForEvent($name),);

        if (!$order
                ->hasState(
                        $this->state_manager
                                ->getPreviousMandatoryState($data['stateName']),
                        $data['instance'])
                && $name != EventManager::EVENT__CREATION) {
            throw new PreviousStateNotFoundException('State not found');
        }

        return $data;
    }

    /**
     * Receives the event name and the order document and creates the appropiate
     * event and state
     *
     * @param string $name The event name
     * @param Celsius\Celsius3Bundle\Document\Order $order The Order document
     */
    public function createEvent($name, Order $order)
    {
        try {
            $data = $this->preValidate($name, $order);
            $this->toPersist[] = $order
                    ->$data['orderDateMethod']($data['date']);
            $this->setEventData($order, $data);
            $this->dm->flush();
            return true;
        } catch (PreviousStateNotFoundException $e) {
            return false;
        }
    }
}
