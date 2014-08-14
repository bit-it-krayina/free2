<?php
namespace Application\Service\Notification\Checker;

use Application\Entity\User;
use Zend\EventManager\Event;
use Application\Service\EntityManagerAwareInterface;
use Application\Service\EntityManagerAwareTrait;

/**
 * Description of UserInfoChecker
 *
 * @author mice
 */
class UserInfoChecker implements EntityManagerAwareInterface
{
	use EntityManagerAwareTrait;

	public function onCheck(Event $event)
	{
		/**
		 * @var User Description
		 */
		$userEntity = $event->getTarget();
		if ( 
				iterator_count ($userEntity->getTags()) > 0
				&&
				$userEntity->getEmail() != ''
				&&
				$userEntity->getFirstName() != ''
				&&
				$userEntity->getLastName() != ''
		) {

			$entityManager = $this->getEntityManager();

			foreach ( $userEntity->getNotifications() as $notification )
			{
				if ( $notification -> getAction() == 'registration' )
				{
					$entityManager -> remove ( $notification );
					$entityManager -> flush ();
				}
			}
		}
	}
}
