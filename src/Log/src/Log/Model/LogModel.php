<?php

/**
 * Model log
 *
 * @author    Vagner
 * @category  Model
 * @package   Log/Model
 * @copyright 2014 P21 Sistemas
 * @version   2.0.0
 */

namespace Log\Model;

use Doctrine\Common\Util\Debug;
use Log\Receiver\LogCadastro;
use Log\Entity\LogCampo;
use Log\Entity\Log;

class LogModel extends \Application\Model\Model
{
	/**
	 * Entity
	 * @var string
	 */
	const ENTITY = 'Log\Entity\Log';
	
	/**
	 * Command log
	 * 
	 * @var CommandLog
	 */
	protected $log;
	
	/**
	 * Set Command Log
	 *  
	 * @param CommandLog $commandLog
	 */
	public function setLog(\Log\Command\CommandLog $commandLog)
	{
		$this->log = $commandLog;
	}
	
	/**
	 * Gravar log
	 * 
	 * @return void
	 */
	public function save($data = null)
	{
		try {
			$this->getRepository()->save($this->log->executar());
		} catch (\Exception $e) {
			error_log($e->getMessage());
		}
	}
}