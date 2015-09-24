<?php

namespace Log\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * LogCampo
 *
 * @ORM\Table(name="log_campo", indexes={@ORM\Index(name="fk_campos_log_idx", columns={"log_id"})})
 * @ORM\Entity
 */
class LogCampo
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="chave", type="string", length=50, nullable=false)
     */
    private $chave;

    /**
     * @var string
     *
     * @ORM\Column(name="valor", type="text", nullable=false)
     */
    private $valor;

    /**
     * @var \Log\Entity\Log
     *
     * @ORM\ManyToOne(targetEntity="Log\Entity\Log", inversedBy="campos")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="log_id", referencedColumnName="id")
     * })
     */
    private $log;

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
     * Set chave
     *
     * @param string $chave
     * @return LogCampo
     */
    public function setChave($chave)
    {
        $this->chave = $chave;

        return $this;
    }

    /**
     * Get chave
     *
     * @return string 
     */
    public function getChave()
    {
        return $this->chave;
    }

    /**
     * Set valor
     *
     * @param string $valor
     * @return LogCampo
     */
    public function setValor($valor)
    {
        $this->valor = $valor;

        return $this;
    }

    /**
     * Get valor
     *
     * @return string 
     */
    public function getValor()
    {
        return $this->valor;
    }

    /**
     * Set log
     *
     * @param \Log\Entity\Log $log
     * @return LogCampo
     */
    public function setLog(\Log\Entity\Log $log = null)
    {
        $this->log = $log;

        return $this;
    }

    /**
     * Get log
     *
     * @return \Log\Entity\Log 
     */
    public function getLog()
    {
        return $this->log;
    }
}
