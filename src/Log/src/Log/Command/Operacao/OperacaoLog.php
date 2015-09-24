<?php

/**
 * Command log das atualizações das entidades
 * 
 * Pattern Command => classe concreta de command 
 * 
 * TRF2014-013482 
 *
 * @author    Vagner
 * @category  Command\Operacao
 * @package   Log/Command/Operacao
 * @copyright 2014 P21 Sistemas
 * @version   2.0.0
 */

namespace Log\Command\Operacao;

use Log\Receiver\LogCadastro;
use Log\Command\CommandLog;
use Autenticacao\Entity\Usuario;
use Log\Entity\Log;
use Zend\Http\PhpEnvironment\Request;
use Zend\ServiceManager\ServiceLocatorInterface;

abstract class OperacaoLog
{
	
	/**
	 * Tipo Operacao
	 * @var string
	 */
	protected $operacao = 'operacao';
	
	/**
	 * LogCadastro
	 * 
	 * @var LogCadastro
	 */
	protected $logCadastro;

	/**
	 * Usuario
	 * 
	 * @var Usuario
	 */
	protected $usuario;
	
	/**
	 * Request
	 * 
	 * @var Request
	 */
	protected $request;
	
	/**
	 * ServiceManager
	 * 
	 * @var ServiceLocatorInterface
	 */
	protected $sm;
	
	/**
	 * Construtor
	 * 
	 * @param  logCadastro
	 * @return void
	 */
	public function __construct(LogCadastro $logCadastro, Usuario $usuario, Request $request)
	{
		$this->logCadastro = $logCadastro;
		$this->usuario = $usuario;
		$this->request = $request;
		$this->logCadastro->setLog(new Log());
	}

    /**
	 * Metodo padrão de execução do log
	 * 
	 * @return Log
	 */
	public function executar()
	{
		$this->logCadastro->setOperacao($this->operacao);
		$this->logCadastro->parse();
		$this->logCadastro->getLog()->setInicio(new \Datetime());
		$this->logCadastro->getLog()->setFim(new \Datetime());
		$this->logCadastro->getLog()->setIp($this->request->getServer('REMOTE_ADDR'));
		$this->logCadastro->getLog()->setMensagem($this->operacao . ' - ' . get_class($this->logCadastro->getEntity()));
		$this->logCadastro->getLog()->setTipo(LogCadastro::TIPO);
		$this->logCadastro->getLog()->setUsuario($this->usuario);
		$this->logCadastro->getLog()->setRoute($this->request->getRequestUri());
		return $this->logCadastro->getLog();
	}
}
