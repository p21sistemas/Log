<?php

/**
 * Tipo log para ser utilizado como entity
 * 
 * Alguns atributos são utilizados como chave da tabela log_campo
 * 
 * Pattern Command => tipos de log são criadas como Receiver no padrão. 
 * 
 * @author    Vagner
 * @category  Receiver
 * @package   Log/Receiver
 * @copyright 2014 P21 Sistemas
 * @version   12.0.0
 */
namespace Log\Receiver;

use Log\Entity\Log;
use Log\Entity\LogCampo;

class LogArquivo implements ReceiverLog
{
	/**
	 * Tipo do log
	 * @var string
	 */
	const TIPO = 'arquivo';
	
    /**
     * @var string
     */
    private $nome;
    
    /**
     * Campos da entity
     * @var array
     */
    private $path;
    
    /**
     * Tipo de arquivo
     * @var unknown
     */
    private $tipo;
    
    /**
     * Entity log
     * @var Log
     */
    protected $log;
    
    /**
     * Construtor
     *
     */
    public function __construct()
    {
    }
    
    /**
     * (non-PHPdoc)
     * @see \Log\Model\ReceiverLog::setLog()
     */
    public function setLog(Log $log)
    {
    	$this->log = $log;
    }
    
    /**
     * (non-PHPdoc)
     * @see \Log\Model\ReceiverLog::getLog()
     */
    public function getLog()
    {
    	return $this->log;
    }
    
    /**
     *
     * @return
     */
    public function getNome()
    {
    	return $this->nome;
    }
    
    /**
     *
     * @param $nome
     */
    public function setNome($nome)
    {
    	$this->nome = $nome;
    }
    
    /**
     *
     * @return
     */
    public function getPath()
    {
    	return $this->path;
    }
    
    /**
     *
     * @param $path
     */
    public function setPath($path)
    {
    	$this->path = $path;
    }
    
    /**
     *
     * @return
     */
    public function getTipo()
    {
    	return $this->tipo;
    }
    
    /**
     *
     * @param $tipo
     */
    public function setTipo($tipo)
    {
    	$this->tipo = $tipo;
    }
    
    /**
     * Parse dos atributos que são chaves da tabela log_campo 
     * 
     * @return \Log\Receiver\LogCadastro
     */
    public function parse()
    {
    	$this->addCampo('nome');
    	$this->addCampo('path');
    	$this->addCampo('tipo');
    	return $this;
    }
    
    /**
     * Converte atributo em LogCampo
     * 
     * @param  string $atributo
     * @return \Log\Receiver\LogCadastro
     */
    protected function addCampo($atributo)
    {
    	// chave
    	$logCampo = new LogCampo();
    	$logCampo->setChave($atributo);
    	// valor
    	$method = 'get' . ucfirst($atributo);
    	// TODO Remover @ e implementar suporte a serialização para as entities
    	$valor = is_array($this->$method()) ? @serialize($this->$method()) : $this->$method();
    	$logCampo->setValor($valor);
    	// add
    	$this->getLog()->addCampo($logCampo);
    	return $this;
    }
}
