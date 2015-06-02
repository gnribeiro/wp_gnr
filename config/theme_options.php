<?php
    return array(
        'fields'  => array(
            array(
                'type'     => 'text',
                'id'       => 'teste_id',
                'title'    => 'Teste Id',
                'args'     => array(
                    'type'      => 'text',
                    'id'        => 'teste_id',
                    'name'      => 'teste_id',
                    'desc'      => 'descrição  teste Id',
                    'class'     => 'css_class'
                )
            ),
            
            array(
                'type'     => 'text',
                'id'       => 'teste_id2',
                'title'    => 'Teste Id2',
                'args'     => array(
                    'id'        => 'teste_id2',
                    'name'      => 'teste_id2',
                    'desc'      => 'descrição  teste Id2',
                    'class'     => 'css_class'
                )
            ),
            array(
                'type'     => 'checkbox',
                'id'       => 'responsive',
                'title'    => 'Teste responsive',
                'args'     => array(
                    'id'        => 'responsive',
                    'value'     => '1',
                    'name'      => 'responsive',
                    'desc'      => 'descrição  responsive',
                    'class'     => 'css_class'
                )
            ),
            array(
                'type'     => 'select',
                'id'       => 'cores',
                'title'    => 'cores',
                'args'     => array(
                    'id'        => 'cores',
                    'options'    => array(
                        'azul'    => 'cor azul',
                        'amarelo' => 'cor amarelo',
                        'verde'   => 'cor verde',
                    ),
                    'name'      => 'cores',
                    'desc'      => 'descrição cores',
                    'class'     => 'cores'
                )
            ),
        )
    )
?>