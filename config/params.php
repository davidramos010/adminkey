<?php

return [
    'adminEmail' => 'admin@example.com',
    'senderEmail' => 'noreply@example.com',
    'senderName' => 'Example.com mailer',
    'contacto' => 'Empresa Reparaciones SL - Telf. 123456789 - Palamos',
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
    'bsDependencyEnabled' => false, // this will not load Bootstrap CSS and JS for all Krajee extensions
    '@plantillas' => "@web/plantillas"
];
