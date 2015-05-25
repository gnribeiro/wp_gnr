<?php
    return array(
        'sections' => array(
            array(
                'id'       => 'gwp_general_section',
                'title'    => 'General Options',
                'callback' => 'gwp_display_section',
                'page'     => 'gwp_theme_options.php',
            )
        ),
        'fields'  => array(
            array(
                'type'     => 'text',
                'id'       => 'teste_id',
                'title'    => 'Teste Id',
                'args'     => array(
                    'type'      => 'text',
                    'id'        => 'teste_id',
                    'name'      => 'teste_id',
                    'desc'      => 'teste Id',
                    'std'       => '',
                    'label_for' => 'gwp_google_analytic_id',
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
                    'desc'      => 'teste Id2',
                    'std'       => '',
                    'label_for' => 'teste_id22',
                    'class'     => 'css_class'
                )
            )
        )
    )
?>