<?php

return [
    'dominio' => 'http://adminkeys.es/',
    'adminEmail' => 'info@sga.cat',
    'senderEmail' => 'info@sga.cat',
    'senderName' => 'www.sga.cat',
    'contacto' => 'Salvadó & Gubert - Telf. 972 600 411 - Palamós',
    'empresa' => 'Salvadó & Gubert',
    'email' => 'info@sga.cat',
    'telefono' => '972 600 411',
    'movil' => '972 819 338',
    'direccion' => 'Carrer López i Puigcerver, 111',
    'poblacion' => '17230 Palamós, Girona',
    'bsVersion' => '4.x', // this will set globally `bsVersion` to Bootstrap 5.x for all Krajee Extensions
    'hail812/yii2-adminlte3' => [
        'pluginMap' => [
            'sweetalert2' => [
                'css' => 'sweetalert2-theme-bootstrap-4/bootstrap-4.min.css',
                'js' => 'sweetalert2/sweetalert2.min.js'
            ],
            'toastr' => [
                'css' => ['toastr/toastr.min.css'],
                'js' => ['toastr/toastr.min.js']
            ],
        ]
    ],
    'index_perfil' => [
        1 => 'index.php',
        2 => 'index.php?r=registro/create'
    ],
    'bsDependencyEnabled' => false, // this will not load Bootstrap CSS and JS for all Krajee extensions
    '@plantillas' => "@web/plantillas",
    '@documents' => "@web/documents",
    'reporteMensual' => [
        'to' => 'davidfernandoramos010@gmail.com',
        'from' => 'soporte@adminkeys.es',
        'subject_ca' => 'Report mensual de claus no tornades.',
        'subject_es' => 'Reporte mensual de llaves no devueltas.',
        'subject_en' => 'Monthly report of unreturned keys.'
    ],
];
