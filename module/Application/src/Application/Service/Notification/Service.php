<?php

namespace Application\Service\Notification;

use Application\Service\EntityManagerAwareInterface;
use Application\Service\EntityManagerAwareTrait;
use Application\Entity\User;
use Application\Entity\Notification;
use Zend\EventManager\ListenerAggregateInterface;
use Zend\EventManager\ListenerAggregateTrait;
use Zend\EventManager\Event;
use Zend\EventManager\EventManagerAwareInterface;
use Zend\EventManager\EventManagerAwareTrait;

class Service implements EntityManagerAwareInterface, EventManagerAwareInterface
{
	use EntityManagerAwareTrait, EventManagerAwareTrait;

	const NOTIFICATION_TYPE_INFO = 'info';

	const EVENT_USER_INFO_UPDATE = 'update_user_info';

	/**
	 * @var Notification
	 */
	protected $notification;


	public function __construct ( $entityManager = null, $events = null )
	{
		$this->setEntityManager($entityManager);
		$this->setEventManager($events);
	}

	public function addNotification(User $user, $action, $title = '', $message = '', $fixUrl = '')
	{
		$notification = new Notification();
		$notification->setUser($user)
			->setTitle($title)
			->setType(self::NOTIFICATION_TYPE_INFO)
			->setMessage($message)
			->setFixUrl($fixUrl)
			->setAction($action)
		;

		$this->getEntityManager()->persist($notification);
		$this->getEntityManager()->flush();

	}

	public function trigger ( $event, $tagret = null)
	{
		$this->getEventManager()->trigger($event, $tagret);
	}


}