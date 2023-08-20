<?php

return [
    'dominio' => 'http://adminkeys.com.es/',
    'adminEmail' => 'info@adminkeys.es',
    'senderEmail' => 'info@adminkeys.es',
    'senderName' => 'www.adminkeys.com.es',
    'contacto' => 'AdminKeys - Telf. 972 410 867 - Santa Cristina d\'aro.',
    'empresa' => 'AdminKEYS',
    'email' => 'info@adminkeys.es',
    'telefono' => '972 410 867',
    'movil' => '611 135 191',
    'direccion' => 'Carrer Pere Gerones, 7',
    'poblacion' => '17246 Santa Cristina d\'aro, Girona',
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
];
