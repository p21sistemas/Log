<?php

namespace Log\ZfTable;

use ZfTable\AbstractTable;
use Doctrine\ORM\QueryBuilder;

/**
 * Log Table
 *
 * @author    Vagner
 * @category  DataTable
 * @package   Log/ZfTable
 * @copyright 2015 P21 Sistemas
 * @version   4.0.0
 */
class Log extends AbstractTable
{
    
    protected $config = array(
        'name' => 'Log',
        'showPagination' => true,
        'showQuickSearch' => true,
        'showItemPerPage' => true,
        'showColumnFilters' => true,
    );

    protected $headers = array(
        'tipo' => array(
            'tableAlias' => 'Log',
            'title' => 'Tipo',
            'width' => '50',
            'filters' => 'text'
        ),
        'nome' => array(
            'tableAlias' => 'Usuario',
            'title' => 'Usuário',
            'width' => '100',
            'filters' => 'text'
        ),
    	'mensagem' => array(
    		'tableAlias' => 'Log',
    		'title' => 'Mensagem',
    		'width' => '250',
    		'filters' => 'text'
    	),
    	'inicio' => array(
    		'tableAlias' => 'Log',
    		'title' => 'Data',
    		'width' => '100',
    		'filters' => 'text',
    		'filter_class' => 'data',
    	),
    	'ip' => array(
    		'tableAlias' => 'Log',
    		'title' => 'IP',
    		'width' => '100',
    		'filters' => 'text'
    	),
        'id' => array(
            'tableAlias' => 'Log',
            'title' => '&nbsp;',
            'width' => '150'
        ) ,
    );
    
    /**
     * 
     * @param \Application\Repository\Repository $repo
     * @param \Zend\Stdlib\Parameters $post
     */
    public function __construct(\Application\Repository\Repository $repo, \Zend\Stdlib\Parameters $post)
    {
        $qb = $repo->createQueryBuilder('Log');
        $qb->innerJoin('Log.usuario', 'Usuario');
        $qb->orderBy('Log.inicio', 'DESC');
        
        $this->setSource($qb);
        $this->setParamAdapter($post);
        
    }

    /**
     * (non-PHPdoc)
     * @see \ZfTable\AbstractTable::init()
     */
    public function init()
    {
    	$this->getHeader('id')->getCell()->addDecorator('visualizar');
    	$this->getHeader('inicio')->addClass('text-center')->getCell()->addDecorator('dateformat', array('format' => 'd/m/Y H:i'));
    }

    /**
     * (non-PHPdoc)
     * @see \ZfTable\AbstractTable::initFilters()
     */
    public function initFilters(QueryBuilder $qb)
    {
    	if ($value = $this->getParamAdapter()->getValueOfFilter('tipo')) {
    		$qb->andWhere($qb->expr()->like('Log.tipo', "'%" . $value . "%'"));
    	}

    	if ($value = $this->getParamAdapter()->getValueOfFilter('nome')) {
    		$qb->andWhere($qb->expr()->like('Usuario.nome', "'%" . $value . "%'"));
    	}

    	if ($value = $this->getParamAdapter()->getValueOfFilter('mensagem')) {
    		$qb->andWhere($qb->expr()->like('Log.mensagem', "'%" . $value . "%'"));
    	}
    	
    	if ($value = $this->getParamAdapter()->getValueOfFilter('ip')) {
    		$qb->andWhere($qb->expr()->like('Log.ip', "'%" . $value . "%'"));
    	}
    	
    	if ($value = $this->getParamAdapter()->getValueOfFilter('inicio')) {
    		$data = \DateTime::createFromFormat('d/m/Y', $value);
    		$data = $data->format('Y-m-d');
    		$qb->andWhere($qb->expr()->like('Log.inicio', "'" . $data . "%'"));
    	}
    }
}