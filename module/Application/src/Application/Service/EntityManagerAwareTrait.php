<?php

namespace Application\Service;

use Doctrine\ORM\EntityManager;

trait EntityManagerAwareTrait
{

	/**
	 * @var \Doctrine\ORM\EntityManager
	 */
	protected $em;

    /**
     * @param EntityManager $em
     */
    public function setEntityManager ( EntityManager $em )
	{
		$this -> em = $em;
	}

    /**
     * @return EntityManager
     */
    public function getEntityManager ()
	{
		if ( null === $this -> em )
		{
			$this -> em = $this -> getServiceLocator () -> get ( 'Doctrine\ORM\EntityManager' );
		}

		return $this -> em;
	}

}