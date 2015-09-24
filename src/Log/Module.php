<?php

namespace Log;

use Zend\Mvc\MvcEvent;
use Doctrine\ORM\Events;
use Log\Event\EntityLog;
use Log\DataTable\LogDataTable;
use Log\Model\LogCampoModel;
use Log\Command\Arquivo\ArquivoLog;
use Log\Receiver\LogArquivo;

class Module
{
	public function onBootstrap(MvcEvent $e)
	{
		$sm  = $e->getApplication()->getServiceManager();
		$em  = $sm->get('doctrine.entitymanager.orm_default');
		$dem = $em->getEventManager();
		$dem->addEventListener(array(Events::onFlush, Events::postFlush), new EntityLog($sm));
		
	}
	
    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__
                )
            )
        );
    }
    
    public function getServiceConfig()
    {

        return array(
            'aliases' => array(
                'EntityManager' => 'Doctrine\ORM\EntityManager'
            ),
            'factories' => array(
                'Log\ZfTable\Log' => function ($sm) {
                    $repo = $sm->get('EntityManager')->getRepository('Log\Entity\Log');
                	return new \Log\ZfTable\Log($repo, $sm->get('Request')->getPost());
                },
                'Log\Command\Arquivo\ArquivoLog' => function ($sm)
                {
                	$usuario = $sm->get('Zend\Authentication\AuthenticationService')->getIdentity();
                	return new ArquivoLog($usuario, $sm->get('Request'));
                },
            )
        );
        
    }
}
