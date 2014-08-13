<?php

namespace Application\Service\Notification;

use Application\Service\EntityManagerAwareInterface;
use Application\Service\EntityManagerAwareTrait;
use CsnUser\Entity\User;
use Application\Entity\Notification;
use Zend\EventManager\ListenerAggregateInterface;
use Zend\EventManager\ListenerAggregateTrait;
use Zend\EventManager\Event;

class Service implements EntityManagerAwareInterface, ListenerAggregateInterface
{
	use EntityManagerAwareTrait, ListenerAggregateTrait;

	const NOTIFICATION_TYPE_INFO = 'info';

	const EVENT_USER_INFO_UPDATE = 'update_user_info';

	/**
	 * @var Notification
	 */
	protected $notification;


	public function __construct ( $entityManager = null )
	{
		$this->setEntityManager($entityManager);
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

	/**
	 *
	 * @return Notification
	 */
	protected function getNotification()
	{
		return $this->notification;
	}



	public function attach ( \Zend\EventManager\EventManagerInterface $events )
	{
		$this->listeners [] = $events->attach (self::EVENT_USER_INFO_UPDATE, [
			$this,
			'updateUserInfo'
		]);
	}

	public function updateUserInfo(Event $event)
	{
		/**
		 * @var User Description
		 */
		$userEntity = $event->getTarget();
		error_log(
			print_r(
				array(
					'Something **********************************',
					$userEntity->getEmail()

			), true));

	}

}