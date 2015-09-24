<?php

/**
 * Command log das atualizações das entidades
 * 
 * Pattern Command => classe concreta de command 
 * 
 * TRF2014-013482 
 *
 * @author    Vagner
 * @category  Command
 * @package   Log/Command/Operacao
 * @copyright 2014 P21 Sistemas
 * @version   2.0.0
 */

namespace Log\Command\Operacao;

use Log\Receiver\LogCadastro;
use Autenticacao\Entity\Usuario;
use Log\Entity\Log;
use Log\Command\CommandLog;

class AtualizarLog extends OperacaoLog implements CommandLog
{
	
	/**
	 * Tipo Operacao
	 * @var string
	 */
	protected $operacao = 'Atualizar';

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
}
