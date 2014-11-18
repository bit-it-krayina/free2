<?php

/**
 * CsnUser - Coolcsn Zend Framework 2 User Module
 *
 */

namespace Application\Service\Factory;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\View\Model\ViewModel;

class ErrorViewFactory implements FactoryInterface
{

	private $serviceLocator;

	public function createService(ServiceLocatorInterface $serviceLocator)
	{
		$this->serviceLocator = $serviceLocator;
		return $this;
	}

	/**
	 * Create error view
	 *
	 * Method to create error view to display possible exceptions
	 *
	 * @return ViewModel
	 */
	public function createErrorView($errorMessage, $exception, $displayExceptions = false, $displayNavMenu = false)
	{
		$viewModel = new ViewModel(array(
			'navMenu' => $displayNavMenu,
			'display_exceptions' => $displayExceptions,
			'errorMessage' => $errorMessage,
			'exception' => $exception,
		));
		$viewModel->setTemplate('error/error');
		$viewModel->setTerminal(true);
		return $viewModel;
	}

}
