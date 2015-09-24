<?php

/**
 * Interface referente tipos de logs
 * 
 * Pattern Command => o Receiver é responsável por encapsular a lógica de execuçao do command
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

interface ReceiverLog
{
	/**
	 * Retornar entity log
	 *  
	 * @return \Log\Entity\Log
	 */
	public function getLog();

	/**
	 * Set log
	 * 
	 * @param log
	 */
	public function setLog(Log $log);
}
