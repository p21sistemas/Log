<?php

/**
 * Tipo log para ser utilizado como entity
 * 
 * Alguns atributos são utilizados como chave da tabela log_campo
 * 
 * Pattern Command => tipos de log são criadas como Receiver no padrão. 
 * 
 * TRF2014-013482 
 *
 * @author    Vagner
 * @category  Receiver
 * @package   Log/Receiver
 * @copyright 2014 P21 Sistemas
 * @version   2.0.0
 */
namespace Log\Receiver;

use Log\Entity\Log;
use Log\Entity\LogCampo;
use Zend\Stdlib\Hydrator\ArraySerializable;
use Zend\Stdlib\Hydrator\ObjectProperty;
use Doctrine\Common\Collections\Collection;

class LogCadastro implements ReceiverLog
{
    
	/**
	 * Tipo do log
	 * @var string
	 */
	const TIPO = 'cadastro';
	
    /**
     * @var string
     */
    private $operacao;
    
    /**
     * Campos da entity
     * @var array
     */
    private $campos;
    
    /**
     * Campos alterados da entity
     * @var array
     */
    private $parcial;
    
    /**
     * Entidade
     * @var object
     */
    protected $entity;
    
    /**
     * Entity log
     * @var Log
     */
    protected $log;
    
    /**
     * Construtor
     *
     * @param  $entidade
     * @param  array $parcial
     * @return void
     */
    public function __construct($entity, array $parcial = null)
    {
    	$this->entity = $entity;
    	$this->parseEntity();
    	$this->setParcial($parcial);
    	$this->parseProxies();
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
     * @see \Log\Receiver\ReceiverLog::getLog()
     */
    public function getLog()
    {
    	return $this->log;
    }
    
    /**
     * Converter entidade em array e atribuir ao atributo campos
     * 
     * @return \Log\Receiver\LogCadastro
     */
    protected function parseEntity()
    {
    	if ($this->entity) {
    		$campos = array();
    		$reflect = new \ReflectionClass($this->entity);
    		$properties = $reflect->getProperties(\ReflectionProperty::IS_PRIVATE); 
			foreach ($properties as $atr) {
				$atr->setAccessible(true);
				$value = $atr->getValue($this->entity);
				if (is_object($value)) {
					if (method_exists($value, 'getId')) {
						$campos[$atr->getName()] = $value->getId();
					} elseif ($value instanceof Collection) {
					    $ids = $value->map(function($value){
					        return $value->getId();
					    });
					    $campos[$atr->getName()] = $ids->toArray();
					}
				} else {
					$campos[$atr->getName()] = $value;
				}
			}
    		$this->setCampos($campos);
    	}
    	return $this;
    }
    
    /**
     * Parse dos atributos que são chaves da tabela log_campo 
     * 
     * @return \Log\Receiver\LogCadastro
     */
    public function parse()
    {
    	$this->addCampo('operacao');
    	$this->addCampo('campos');
    	$this->addCampo('parcial');
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
    
    /**
     * Retornar entity
     * 
     * @return object
     */
    public function getEntity()
    {
    	return $this->entity;
    }
    
    /**
     * Set operação
     * 
     * @param  string $operacao
     * @return \Log\Receiver\LogCadastro
     */
    public function setOperacao($operacao)
    {
		$this->operacao = $operacao;	
		return $this;	    		
    }
    
    /**
     * Retornar operação
     * 
     * @return string
     */
    public function getOperacao()
    {
    	return $this->operacao;
    }
    
    /**
     * Set campos
     * 
     * @param array $campos
     * @return \Log\Receiver\LogCadastro
     */
    public function setCampos(array $campos)
    {
    	$this->campos = $campos;
    	return $this;
    }
    
    /**
     * Retornar campos
     * 
     * @return array
     */
    public function getCampos()
    {
    	return $this->campos;
    }
    
    /**
     * Set parcial
     * 
     * @param  array $parcial
     * @return \Log\Receiver\LogCadastro
     */
    public function setParcial(array $parcial)
    {
    	$this->parcial = $parcial;
    	return $this;
    }
    
    /**
     * Retornar parcial
     * 
     * @return array
     */
    public function getParcial()
    {
    	return $this->parcial;
    }
    
    /**
     * Procura pelos proxies no campo parcial e os substiui pelo seu respectivo
     * id.
     * 
     * @param array $parcial
     * @return void
     */
    protected function parseProxies()
    {
        foreach ($this->parcial as $field => $values) {
            foreach ($values as $i => $value) {
                $hasId = method_exists($value, 'getId');
                if ($hasId && $value instanceof \Doctrine\ORM\Proxy\Proxy) {
                    $this->parcial[$field][$i] = $value->getId();
                }
            }
        }
    }
    
}
