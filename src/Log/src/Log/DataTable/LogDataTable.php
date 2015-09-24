<?php
namespace Log\DataTable;

use DataTable\Model\DataTable;

class LogDataTable extends DataTable
{

    /**
     * (non-PHPdoc)
     *
     * @see \DataTable\Model\DataTable::customizeRow()
     */
    public function customizeRow()
    {
        $this->item['id'] = $this->link($this->item['id']);
        
        $campos = '';
        foreach ($this->item['campos'] as $campo) {
            $campos .= implode("<br />", $campo);
        }
        $this->item['campos']['valor'] = $campos;
        
    }
    
    /**
     *
     * @param int $id
     */
    public function link($id)
    {
        $route = $this->getRoute();
        $helper = $this->getViewHelperManager();
        $link = $helper->get('link');
    
        $urlParams = array(
            'controller' => 'index',
            'action' => 'visualizar',
            'id' => $id
        );
    
        $attributes = array(
            'value' => 'Visualizar',
            'class' => 'btn btn-xs default',
            'class_i' => 'fa fa-search',
            'rel' => 'p21Ajax',
        );
    
        return $link('log', $urlParams, $attributes);
    }  

    
    /**
     * (non-PHPdoc)
     * 
     * @see \DataTable\Model\DataTable::getCountAllResults()
     */
    public function getCountAllResults()
    {
        $qb = $this->getModel()
        ->getRepository()
        ->createQueryBuilder($this->tableName)
        ->select('count(distinct ' . $this->tableName . '.' . $this->rootEntityIdentifier . ')');
        $this->setAssociations($qb);
    
    
        return (int) $qb->getQuery()->getSingleScalarResult();
    }
    
    /**
     * (non-PHPdoc)
     * 
     * @see \DataTable\Model\DataTable::getCountFilteredResults()
     */
    public function getCountFilteredResults()
    {
        $qb = $this->getModel()
        ->getRepository()
        ->createQueryBuilder($this->tableName);
        $qb->select('count(distinct ' . $this->tableName . '.' . $this->rootEntityIdentifier . ')');
        $this->setAssociations($qb);
        $this->setWhere($qb);
    
        $qb->resetDQLPart("groupBy");
    
        return (int) $qb->getQuery()->getSingleScalarResult();
    }
        
    /**
     * (non-PHPdoc)
     * 
     * @see \DataTable\Model\DataTableFunctions::convertDate()
     */
    public function convertDate(&$item)
    {
        foreach ($item as $k => $v) {
            if (is_array($v)) {
                $this->convertDate($item[$k]);
            } elseif ($v instanceof \Datetime) {
                $item[$k] = $v->format('d/m/Y H:m:s');
            }
        }
    }
}