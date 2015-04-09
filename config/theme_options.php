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
                'id'       => 'google_analytic_id',
                'title'    => 'Google Analytic Id',
                'callback' => 'gwp_display_setting',
                'page'     => 'gwp_theme_options.php',
                'section'  => 'gwp_general_section',
                'args'     => array(
                    'type'      => 'text',
                    'id'        => 'gwp_google_analytic_id',
                    'name'      => 'gwp_google_analytic_id',
                    'desc'      => 'The Google Analytic Id',
                    'std'       => '',
                    'label_for' => 'gwp_google_analytic_id',
                    'class'     => 'css_class'
                )
            )
        )
    )
?>