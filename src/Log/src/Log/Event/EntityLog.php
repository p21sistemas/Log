<?php
/**
 * Evento para registrar log de todas as alteraçoes nas entidades
 *
 * TRF2014/013448
 * TRF2014/013482
 * 
 * @author    Vagner
 * @category  Event
 * @package   Log\Event
 * @copyright 2014 P21 Sistemas
 * @version   2.0.0
 */
namespace Log\Event;

use Doctrine\ORM\Event\OnFlushEventArgs;
use Doctrine\ORM\Event\PostFlushEventArgs;
use Doctrine\ORM\EntityManager;

use Log\Entity\LogCampo;
use Log\Entity\Log;
use Log\Receiver\LogCadastro;
use Log\Model\LogModel;
use Log\Command\CommandLog;
use Log\Command\Operacao\InserirLog;
use Log\Command\Operacao\AtualizarLog;
use Log\Command\Operacao\RemoverLog;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Authentication\AuthenticationService as AuthService;
use Zend\Http\PhpEnvironment\Request;

class EntityLog
{
	/**
	 * Model
	 * @var \Application\Model\Model
	 */
	private $model;
	
	/**
	 * Entity manager
	 * @var EntityManager
	 */
	private $em;
	
	/**
	 * Auth
	 * @var AuthService
	 */
	private $auth;
	
	/**
	 * Request
	 * @var Request
	 */
	private $request;
	
	/**
	 * Entities que deverão ser logadas
	 * @var array
	 */
	private $entities;
	
	/**
	 * 
	 * @var ServiceLocatorInterface
	 */
	private $sm;
	
	/**
	 * Contrutor 
	 * 
	 * @param  ServiceLocatorInterface $sm
	 * @return void
	 */
	public function __construct(ServiceLocatorInterface $sm)
	{
	    $this->sm = $sm;
		$this->auth = $sm->get('Zend\Authentication\AuthenticationService');
		$this->request = $sm->get('Request');
		// config
		$config = $sm->get('config');
		$this->entities = $config['entity_log']['entities'];
	}
	
    /**
     * Evento para executar antes do flush()
     * 
     * Antes do flush() é armazenado o model que possui o log da entidade preparada para persistir (ScheduledEntity)
     * 
     * O model é armazenado neste momento pois somente no onFlush() é possível resgatar entidades pelo getScheduledEntity***()
     * 
     * @param  OnFlushEventArgs $eventArgs
     * @return boolean
     */
    public function onFlush(OnFlushEventArgs $eventArgs)
    {
    	$this->em = $eventArgs->getEntityManager();
    	$uow = $this->em->getUnitOfWork();
    	
    	foreach ($uow->getScheduledEntityInsertions() as $entity) {
    		$this->registrar($entity, 'Log\Command\Operacao\InserirLog');
    	}
    
    	foreach ($uow->getScheduledEntityUpdates() as $entity) {
    		$this->registrar($entity, 'Log\Command\Operacao\AtualizarLog');
    	}
    
    	foreach ($uow->getScheduledEntityDeletions() as $entity) {
    		$this->registrar($entity, 'Log\Command\Operacao\RemoverLog');
    	}
    
    	return true;
    }
    
    /**
     * Registro do log para a entidade
     * 
     * @param  object $entity
     * @param  string $command (nome da class)
     * @return boolean
     */
    protected function registrar($entity, $command)
    {
    	if (!$this->hasOnFlush($entity)) {
    		return false;
    	}
    	
    	$model = new LogModel($this->em);
    	// dados alterados
    	$parcial = $this->em->getUnitOfWork()->getEntityChangeSet($entity);
    	// log
    	$logCadastro = new LogCadastro($entity, $parcial);
    	$commandLog = new $command($logCadastro, $this->auth->getIdentity(), $this->request);
    	// armazenar model
    	$model->setLog($commandLog);
    	$this->model = $model;
    	return true;
    }
    
    /**
     * Evento para executar após do flush()
     * 
     * Após o flush() é executado o model que possui o log da entidade
     * 
     * O model é salvo no postFlush() pois pode ser que ocorra algum erro durante o flush()
     *       
     * @param  PostFlushEventArgs $eventArgs
     * @return boolean
     */
    public function postFlush(PostFlushEventArgs $eventArgs)
    {
    	if (!$this->hasPostFlush($eventArgs)) {
    		return false;
    	}
    	if ($this->model) {
			$this->model->save();
    	}
		return true;
    }

    /**
     * Verifica se a entidade é válida para onFlush()
     * 
     * @param  object $entity
     * @return boolean
     */
    protected function hasOnFlush($entity)
    {
    	if (in_array(get_class($entity), $this->entities)) {
    		return true;
    	}
    	return false;
    }  
    
    /**
     * Verifica se tem entidades válidas para postFlush()
     * 
     * @param  PostFlushEventArgs $eventArgs
     * @return boolean
     */
    protected function hasPostFlush(PostFlushEventArgs $eventArgs)
    {
    	$entities = $eventArgs->getEntityManager()->getUnitOfWork()->getIdentityMap();
    	// se for log não registra
    	if (array_key_exists('Log\Entity\Log', $entities) || array_key_exists('Log\Entity\LogCampo', $entities)) {
    		return false;
    	}
    	return true;
    }
}