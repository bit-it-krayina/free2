<?php

namespace Application\Controller;


use Zend\Mvc\Controller\AbstractActionController;
use Application\Service\EntityManagerAwareInterface;
use Application\Service\EntityManagerAwareTrait;
use Application\Entity\Order;
use Application\Entity\Teacher;
use Zend\View\Model\JsonModel;

class AjaxController extends AbstractActionController implements EntityManagerAwareInterface
{

    use EntityManagerAwareTrait;

    /**
     * @todo-mice добавить отправку письма по урлу в настройках.
     */
    public function contactMeAction ()
    {
	$email = $this -> params () -> fromPost ( 'inputEmail', '' );
	$name = $this -> params () -> fromPost ( 'inputName', '' );
	$message = $this -> params () -> fromPost ( 'inputMessage', '' );

	$result = 'failure';
	$id = false;
	/**
	 * Сохранение заказа в базе
	 */
	if ( !empty ( $email ) && !empty ( $name ) && !empty ( $message ) )
	{
	    $orderEntity = new Order();
	    $orderEntity
		    -> setCreated ( time() )
		    -> setCustomerName ( $name )
		    -> setCustomerEmail ( $email )
		    -> setOrderDescription ( $message )
		    -> setStateId ( Order::STATE_NEW )
		    -> setTeacherId ( Teacher::DEFAULT_YASILDA_ID )
		    ;
	    $em = $this ->  getEntityManager();
	    $em->persist($orderEntity);
	    $em -> flush();

	    $id = $orderEntity -> getId();
	    $result = 'success';

	    {
		/**
		 * @var \Application\Service\EmailSender Description
		 */
		$mailer = $this->getServiceLocator()->get('mailer');
		if ( ! $mes = $mailer->sendOrderMailToAdmin($orderEntity) )
		{
		    $result = 'failure';
}

	    }
	}


	$this -> redirect () -> toRoute (
		'contact_thanks', array ( 'result' => $result, 'order_id' => $id ) );
    }

}
