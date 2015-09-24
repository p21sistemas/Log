<?php

/**
 * Interface referente os commands que serão utilizados na montagem do log
 * 
 * Pattern Command => interface comum para a gravação de logs
 * 
 * TRF2014-013482 
 *
 * @author    Vagner
 * @category  Model
 * @package   Log/Command
 * @copyright 2014 P21 Sistemas
 * @version   2.0.0
 */

namespace Log\Command;

use Log\Entity\Log;

interface CommandLog
{
	/**
	 * Metodo padrão de execução do log
	 * 
	 * @return Log
	 */
	public function executar();

	/**
	 * Retornar o conteudo do log em html
	 * 
	 * @return string
	 */
	public function toHtml();

	/**
	 * Retornar o conteudo do log em texto
	 *
	 * @return string
	 */
	public function toString();
}
