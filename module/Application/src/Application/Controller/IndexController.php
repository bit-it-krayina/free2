<?php

namespace Application\Controller;


use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Application\Model\Contact;
use Application\Service\EntityManagerAwareInterface;
use Application\Service\EntityManagerAwareTrait;
use Application\Service\MenuTrait;
use Application\Service\ControlUtils;
use Application\Form\Login;

class IndexController extends AbstractActionController implements EntityManagerAwareInterface
{

	use EntityManagerAwareTrait,
	 MenuTrait,
	 ControlUtils
			;


	public function indexAction ()
	{

		$review = $this
				-> getEntityManager ()
				-> getRepository ( 'Application\Entity\Review' )
				-> findBy ( ['moderated' => 1 ], ['review_date' => 'DESC' ], 1 );
		$contacts = $this -> getServiceLocator () -> get ( 'Config' );
		$contact = new Contact ( $contacts[ 'contacts' ] );

		return $this->createViewModel('application/index/index',
				array (

					'last_review' => current ( $review ),
					'contact' => $contact
				) );
	}

	public function aboutAction ()
	{
//	$mailer = $this->getServiceLocator()->get('mailer');
		return new ViewModel();
	}

	public function contactsAction ()
	{
		return new ViewModel();
	}

	public function servicesAction ()
	{
		return new ViewModel();
	}

	public function thanksAction ()
	{
		$result = $this -> params () -> fromRoute ( 'result' );
		$orderId = $this -> params () -> fromRoute ( 'order_id' );
		$order = false;

		if ( !empty ( $orderId ) )
		{
			$order = $this
					-> getEntityManager ()
					-> getRepository ( 'Application\Entity\Order' )
					-> find ( $orderId );
//	error_log (
//		var_export ( array ( $order ), true )
//	);
		}

		return new ViewModel (
				array (
			'result' => $result,
			'order_id' => $orderId,
			'order' => $order,
		) );
	}

}
