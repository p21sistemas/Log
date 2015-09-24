<?php
namespace Log;

return array(
    'router' => array(
        'routes' => array(
            'log' => array(
                'type' => 'Literal',
                'options' => array(
                    'route' => '/log',
                    'defaults' => array(
                        '__NAMESPACE__' => 'Log\Controller',
                        'controller' => 'Index',
                        'action' => 'index'
                    )
                ),
                'may_terminate' => true,
                'child_routes' => array(
                    'default' => array(
                        'type' => 'Segment',
                        'options' => array(
                            'route' => '/[:controller[/:action[/:id]]][/]',
                            'constraints' => array(
                                'controller' => '[a-zA-Z][a-zA-Z0-9_-]*',
                                'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                                'id' => '[0-9]+'
                            ),
                            'defaults' => array()
                        )
                    )
                )
            )
        )
    ),
    'controllers' => array(
        'invokables' => array(
            'Log\Controller\Index' => 'Log\Controller\IndexController'
        )
    ),
    'view_manager' => array(
        'template_path_stack' => array(
            __DIR__ . '/../view'
        )
    ),
	'service_manager' => array(
		'invokables' => array(
			'Log\Model\LogModel' => 'Log\Model\LogModel',
			'Log\Model\LogCampoModel' => 'Log\Model\LogCampoModel',
		),
	),
    'doctrine' => array(
        'driver' => array(
            __NAMESPACE__ . '_driver' => array(
                'class' => 'Doctrine\ORM\Mapping\Driver\AnnotationDriver',
                'cache' => 'array',
                'paths' => array(
                    __DIR__ . '/../src/' . __NAMESPACE__ . '/Entity'
                )
            ),
            'orm_default' => array(
                'drivers' => array(
                    __NAMESPACE__ . '\Entity' => __NAMESPACE__ . '_driver'
                )
            )
        )
    ),
    'entity_log' => array(
        'entities' => array(
            'Cadastro\Entity\Apresentante',
            'Cadastro\Entity\ApresentanteEncargo',
            'Cadastro\Entity\Assessoria',
            'Cadastro\Entity\Banco',
            'Cadastro\Entity\Cartorio',
            'Cadastro\Entity\ContaRepasse',
            'Cadastro\Entity\Convenio',
            'Cadastro\Entity\Encargo',
            'Cadastro\Entity\EncargoValor',
            'Cadastro\Entity\EncargoVigencia',
            'Cadastro\Entity\Especie',
            'Cadastro\Entity\Feriado',
            'Cadastro\Entity\Instituicao',
            'Cadastro\Entity\Monitorado',
            'Cadastro\Entity\MotivoDevolucao',
            'Cadastro\Entity\MotivoProtesto',
            'Cadastro\Entity\Municipio',
            'Cadastro\Entity\Ocorrencia',
            'Cadastro\Entity\Parametro',
            
            'Acl\Entity\Acao',
            'Acl\Entity\Funcao',
            'Acl\Entity\FuncaoAcao',
            'Acl\Entity\Perfil',
            
            'Autenticacao\Entity\Usuario',
            
            'Cra\Entity\Alerta',
            'Cra\Entity\Confirmacao',
            'Cra\Entity\DevedorRegistro',
            'Cra\Entity\Devolucao',
            'Cra\Entity\Registro',
            'Cra\Entity\Remessa',
            'Cra\Entity\RemessaHeader',
            'Cra\Entity\RemessaTrailler',
            
            'Distribuicao\Entity\HistoricoDistribuicao',
            'Distribuicao\Entity\Processo',
            'Distribuicao\Entity\ProcessoTitulo',
            'Distribuicao\Entity\ProcessoTituloEncargo',
            'Distribuicao\Entity\ProcessoTituloEnvolvido',
            'Distribuicao\Entity\RemessaCartorio',
            
            'Titulo\Entity\Envolvido',
            'Titulo\Entity\EnvolvidoTipo',
            'Titulo\Entity\SituacaoTitulo',
            'Titulo\Entity\Titulo',
            'Titulo\Entity\TituloEncargo',
            
    	)
    )
);