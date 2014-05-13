<?php

namespace Application\Service;

trait MenuTrait
{

	/**
	 * Возвращает массив с сущностями преподователей
	 * @return array
	 */
	public function getTeachers ()
	{
		return $this
						-> getEntityManager ()
						-> getRepository ( 'Application\Entity\Teacher' )
						-> findAll ();
	}

	public function getLoggedUser ()
	{
		if ( $this -> getAuthenticationService () -> hasIdentity () )
		{
			return $this -> getAuthenticationService () -> getIdentity ();
		}
		
		return ['username' => 'Guest'];
	}

}