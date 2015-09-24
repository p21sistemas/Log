<?php

namespace Log\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Zend\Stdlib\Hydrator\ClassMethods;
use Zend\Stdlib\Hydrator\HydratorInterface;
use Log\Model\ReceiverLog;

/**
 * Log
 *
 * @ORM\Table(name="log", indexes={@ORM\Index(name="fk_log_usuario_idx", columns={"usuario_id"})})
 * @ORM\Entity(repositoryClass="Log\Repository\LogRepo")
 */
class Log
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    protected $id;

    /**
     * @var string
     *
     * @ORM\Column(name="tipo", type="string", length=20, nullable=true)
     */
    protected $tipo;
    
    /**
     * @var \DateTime
     *
     * @ORM\Column(name="inicio", type="datetime", nullable=true)
     */
    protected $inicio;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fim", type="datetime", nullable=true)
     */
    protected $fim;

    /**
     * @var string
     *
     * @ORM\Column(name="mensagem", type="string", length=300, nullable=true)
     */
    protected $mensagem;

    /**
     * @var string
     *
     * @ORM\Column(name="ip", type="string", length=50, nullable=true)
     */
    protected $ip;
    
    /**
     * @var string
     *
     * @ORM\Column(name="route", type="string", length=150, nullable=true)
     */
    protected $route;

    /**
     * @var \Autenticacao\Entity\Usuario
     *
     * @ORM\ManyToOne(targetEntity="Autenticacao\Entity\Usuario", cascade={"persist"})
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="usuario_id", referencedColumnName="id")
     * })
     */
    protected $usuario;
    
    /**
     * @ORM\OneToMany(targetEntity="Log\Entity\LogCampo", mappedBy="log", cascade={"persist"})
     */
    protected $campos;
    
    /**
     * Construtor 
     * 
     */
    public function __construct()
    {
    	$this->campos = new ArrayCollection();
    }

    /**
     * Get id
     *
     * @return integer
     */
    public function setId($id)
    {
    	$this->id = $id;
    	return $this;
    }
    
    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set tipo
     *
     * @param  string $tipo
     * @return Log
     */
    public function setTipo($tipo)
    {
    	$this->tipo = $tipo;
    
    	return $this;
    }
    
    /**
     * Get tipo
     *
     * @return string
     */
    public function getTipo()
    {
    	return $this->tipo;
    }
    
    /**
     * Set inicio
     *
     * @param \DateTime $inicio
     * @return Log
     */
    public function setInicio($inicio)
    {
        $this->inicio = $inicio;

        return $this;
    }

    /**
     * Get inicio
     *
     * @return \DateTime 
     */
    public function getInicio()
    {
        return $this->inicio;
    }

    /**
     * Set fim
     *
     * @param \DateTime $fim
     * @return Log
     */
    public function setFim($fim)
    {
        $this->fim = $fim;

        return $this;
    }

    /**
     * Get fim
     *
     * @return \DateTime 
     */
    public function getFim()
    {
        return $this->fim;
    }

    /**
     * Set mensagem
     *
     * @param string $mensagem
     * @return Log
     */
    public function setMensagem($mensagem)
    {
        $this->mensagem = $mensagem;

        return $this;
    }

    /**
     * Get mensagem
     *
     * @return string 
     */
    public function getMensagem()
    {
        return $this->mensagem;
    }

    /**
     * Set ip
     *
     * @param string $ip
     * @return Log
     */
    public function setIp($ip)
    {
        $this->ip = $ip;

        return $this;
    }

    /**
     * Get ip
     *
     * @return string 
     */
    public function getIp()
    {
        return $this->ip;
    }
    
    /**
     * Set route
     *
     * @param  string $route
     * @return Log
     */
    public function setRoute($route)
    {
        $this->route = $route;
        return $this;
    }
    
    /**
     * Retornar route
     *
     * @return string
     */
    public function getRoute()
    {
        return $this->route;
    }    

    /**
     * Set usuario
     *
     * @param \Autenticacao\Entity\Usuario $usuario
     * @return Log
     */
    public function setUsuario(\Autenticacao\Entity\Usuario $usuario = null)
    {
        $this->usuario = $usuario;

        return $this;
    }
    
    /**
     * Adicionar logCampo
     * 
     * @param  LogCampo $logCampo
     * @return void
     */
    public function addCampo(LogCampo $logCampo)
    {
    	$logCampo->setLog($this);
    	$this->campos->add($logCampo);
    }
    
    public function addCampos(ArrayCollection $campos)
    {
    	foreach ($campos as $logCampo) {
    		$logCampo->setLog($this);
    		$this->campos->add($logCampo);
    	}
    }

    public function removeCampos(ArrayCollection $campos)
    {
    	foreach ($campos as $logCampo) {
    		$this->campos->removeElement($logCampo);
    	}
    }
    
    /**
     * Retorna as entidades de campos
     * 
     * @return \Doctrine\Common\Collections\ArrayCollection
     */
    public function getCampos()
    {
    	return $this->campos;
    }

    /**
     * Get usuario
     *
     * @return \Autenticacao\Entity\Usuario 
     */
    public function getUsuario()
    {
        return $this->usuario;
    }
    
    /**
     * Tratamento do objeto de log da subclasse de Log
     * 
     * @param  Log $entity
     * @return Log
     */
    public function hydrate(\Log\Receiver\ReceiverLog $log)
    {
    	$log->setLog($this);
    	
    	foreach ($this->getCampos() as $campo) {
    		$atr = $campo->getChave();
    		$method = 'set' . ucfirst($atr);
    		if (method_exists(get_class($log), $method)) {
    			$log->$method($campo->getValor());
    		}
    	}
    	return $log;
    }

    public function toArray()
    {
    	return get_object_vars($this);
    }
    
    
}
