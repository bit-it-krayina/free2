<?php
namespace Application\Service;


use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Config\Config;
use Zend\Mail\Transport\Smtp;
use Zend\Mail\Transport\SmtpOptions;

class EmailSenderFactory implements FactoryInterface
{
    private $emailSettings = array (
	'host' => 'smtp.gmail.com',
	'port' => 587,
	'connection_class'  => 'plain',
	'connection_config' => array(
	    'username' => 'yas.sprachschule@gmail.com',
	    'password' => 'a14$BB6#',
	    'ssl'      => 'tls',
	),



    );

    public function createService(ServiceLocatorInterface $serviceLocator)
    {
		$emailConfig = new Config($this ->emailSettings);
		$options = new SmtpOptions($this ->emailSettings);
		$transport = new Smtp($options);
		return new EmailSender($transport);
    }
}
