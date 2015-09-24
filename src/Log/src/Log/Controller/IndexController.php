<?php

namespace Log\Controller;
	
use Zend\View\Model\ViewModel;
use Application\Controller\AbstractActionController;
use Application\Common\Message;

class IndexController extends AbstractActionController
{
	/**
	 * Index
	 *
	 * @return \Zend\View\Model\ViewModel
	 */
	public function indexAction()
	{
		if ($this->getRequest()->isXmlHttpRequest()) {
			$table = $this->getServiceLocator()->get('Log\ZfTable\Log');
			return $this->getResponse()->setContent($table->render('html'));
		}

		$view = new ViewModel(array());
		$view->setTerminal($this->getRequest()->isXmlHttpRequest());

		return $view;
	}

	/**
	 * Visualizar
	 *
	 * @return \Zend\View\Model\ViewModel
	 */
	public function visualizarAction()
	{
		$id = $this->params('id', null);
        $model = $this->getServiceLocator()->get('Log\Model\LogModel');
        $log = $model->getRepository()->find($id);
        
        $view = new ViewModel();
        $view->setTerminal($this->getRequest()->isXmlHttpRequest());
        $view->setVariable("log", $log);
        return $view;
	}
}