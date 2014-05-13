<?php
namespace Application\Service;

use Zend\Mail\Transport\TransportInterface;
use Application\Entity\Order;
use Zend\Mail\Message;
use Zend\Mail\Transport\Exception\RuntimeException;
class EmailSender
{
    const ADMIN_EMAIL = 'yas.sprachschule@gmail.com';
    /**
     * @var TransportInterface 
     */
    private $transport;
//    private $ADMIN_EMAIL = ['imychkova@gmail.com', 'yasilda@yandex.ru'];
    
    public function __construct ( TransportInterface $transport )
    {
	$this ->transport = $transport;
    }
    
    
    public function sendOrderMailToAdmin(Order $orderEntity)
    {
	$message = new Message();
	$message -> setEncoding ( "UTF-8" );
	$message -> addFrom ( $orderEntity -> getCustomerEmail (),
			$orderEntity -> getCustomerName () )
		-> addTo ( self::ADMIN_EMAIL )
		-> setSubject ( "New Order Created FOR: " . $orderEntity -> getTeacherId () );
	$message -> setBody ( $orderEntity -> getOrderDescription () );
	try
	{
	    $this -> transport -> send($message);
	    return true;
	}
	catch ( RuntimeException $exc )
	{
	    error_log( $exc -> getTraceAsString ());
	    return false;
	}

	return $message -> toString();
	
    }
}
