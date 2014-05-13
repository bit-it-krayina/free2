<?php

namespace Application\Controller;


use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Doctrine\ORM\EntityManager;
use Application\Service\EntityManagerAwareInterface;
use Application\Service\EntityManagerAwareTrait;
use Application\Service\MenuTrait;

class TeachersController extends AbstractActionController implements EntityManagerAwareInterface
{

	use EntityManagerAwareTrait,
	 MenuTrait;

	public function indexAction ()
	{
		$teachers = $this -> getTeachers ();

		$view = new ViewModel ( array (
			'teachers' => $teachers
		) );
		return $view;
	}

	public function previewAction ()
	{
		$teacher = $this -> params ( 'teacher' );
		$teachers = $this -> getTeachers ();

		return new ViewModel ( array (
			'teacher' => $teacher,
			'teachers' => $teachers,
		) );
	}

}
