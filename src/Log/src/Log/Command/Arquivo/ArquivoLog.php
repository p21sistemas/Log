<?php

/**
 * Command log para operações de arquivos
 * 
 * Pattern Command => classe concreta de command 
 * 
 * @author    Vagner
 * @category  Command
 * @package   Log/Command/Operacao
 * @copyright 2014 P21 Sistemas
 * @version   12.0.0
 */

namespace Log\Command\Arquivo;

use Log\Receiver\LogArquivo;
use Autenticacao\Entity\Usuario;
use Log\Entity\Log;
use Log\Command\CommandLog;
use Zend\Http\PhpEnvironment\Request;

class ArquivoLog implements CommandLog
{
	
	/**
	 * LogArquivo
	 * @var LogArquivo
	 */
	protected $logArquivo;
	
	/**
	 * Usuario
	 * @var Usuario
	 */
	protected $usuario;
	
	/**
	 * Request
	 * @var Request
	 */
	protected $request;
	
	/**
	 * Construtor
	 *
	 * @param  logArquivo
	 * @return void
	 */
	public function __construct(Usuario $usuario, Request $request)
	{
		$this->usuario = $usuario;
		$this->request = $request;
	}
	
	/**
	 * Metodo padrão de execução do log
	 *
	 * @return Log
	 */
	public function executar()
	{
		$this->logArquivo->parse();
		$this->logArquivo->getLog()->setInicio(new \Datetime());
		$this->logArquivo->getLog()->setFim(new \Datetime());
		$this->logArquivo->getLog()->setIp($this->request->getServer('REMOTE_ADDR'));
		$this->logArquivo->getLog()->setMensagem('Log arquivo de ' . $this->logArquivo->getTipo() . ': ' . $this->logArquivo->getNome());
		$this->logArquivo->getLog()->setTipo(LogArquivo::TIPO);
		$this->logArquivo->getLog()->setUsuario($this->usuario);
		$this->logArquivo->getLog()->setRoute($this->request->getRequestUri());
		return $this->logArquivo->getLog();
	}
	
	/**
	 * Retornar o conteudo do log em html
	 *
	 * @return string
	 */
	public function toHtml()
	{
	}

	/**
	 * Retornar o conteudo do log em texto
	 *
	 * @return string
	 */
	public function toString()
	{
	}

	/**
	 * Set log arquivo
	 * @param \Log\Receiver\LogArquivo $logArquivo
	 */
	public function setLogArquivo($logArquivo)
	{
	    $this->logArquivo = $logArquivo;
	    $this->logArquivo->setLog(new Log());
	}
}
