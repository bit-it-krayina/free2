<?php

namespace Application\Service\Notification;

use Application\Service\EntityManagerAwareInterface;
use Application\Service\EntityManagerAwareTrait;
use CsnUser\Entity\User;
use Application\Entity\Notification;


class Service implements EntityManagerAwareInterface
{

	use EntityManagerAwareTrait;

	const NOTIFICATION_TYPE_INFO = 'info';


	/**
	 * @var Notification
	 */
	protected $notification;


	public function __construct ( $entityManager = null )
	{
		$this->setEntityManager($entityManager);
	}

	public function addNotification(User $user, $title = '', $message = '', $fixUrl = '')
	{
		$notification = new Notification();
		$notification->setUser($user)
			->setTitle($title)
			->setType(self::NOTIFICATION_TYPE_INFO)
			->setMessage($message)
			->setFixUrl($fixUrl)
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


}