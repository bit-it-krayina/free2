<?php

namespace Application\Model;

use Application\Entity\User as UserEntity;
use Zend\Stdlib\Hydrator\ClassMethods as ClassMethodsHydrator;

/**
 * Description of User
 *
 * @author mice
 */
class User
{

	private $entity = null;

	/**
	 *
	 * @var ClassMethodsHydrator
	 */
	private $hydrator = null;

	public function __construct ( UserEntity $entity = null )
	{
		if ( empty ( $entity ) )
		{
			$this -> entity = new UserEntity();
		}
		else
		{
			$this -> entity = $entity;
		}

		$this -> hydrator = new ClassMethodsHydrator();

	}

	public function populate ( $params = [ ] )
	{
		foreach($params as $key => $value)
		{
			switch ( $key )
			{
				case 'workExperience':
					$workExperience = $entityManager -> find ( 'Application\Entity\WorkExperience', $value );
					$user -> $key = $workExperience;
					break;
				case 'location':
				case 'resume':
					$privateInfo = $user -> getPrivateInfo ();
					if ( empty ( $privateInfo ) )
					{
						$privateInfo = new UserPrivate();
						$entityManager -> persist ( $privateInfo );
						$entityManager -> flush ();
						$user -> setPrivateInfo ( $privateInfo );
					}

					$privateInfo -> $key = $value;
					break;
				case 'birthDay':
					$privateInfo = $user -> getPrivateInfo ();
					if ( empty ( $privateInfo ) )
					{
						$privateInfo = new UserPrivate();
						$entityManager -> persist ( $privateInfo );
						$entityManager -> flush ();
						$user -> setPrivateInfo ( $privateInfo );
					}
					$privateInfo -> setBirthDay ( new \DateTime ( $value ) );
					break;
				default:
					$user -> $key = $value;
					break;
			}
		}

		$this -> hydrator -> hydrate ( $params, $this -> entity );




		return $this -> entity;

	}

	public function toArray ()
	{
		return $this -> hydrator -> extract ( $this -> entity );

	}

}
