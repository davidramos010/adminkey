<?php

$vendorDir = dirname(__DIR__);

return array (
  'yiisoft/yii2-faker' => 
  array (
    'name' => 'yiisoft/yii2-faker',
    'version' => 'dev-master',
    'alias' => 
    array (
      '@yii/faker' => $vendorDir . '/yiisoft/yii2-faker/src',
    ),
  ),
  'yiisoft/yii2-debug' => 
  array (
    'name' => 'yiisoft/yii2-debug',
    'version' => '2.1.19.0',
    'alias' => 
    array (
      '@yii/debug' => $vendorDir . '/yiisoft/yii2-debug/src',
    ),
  ),
  'yiisoft/yii2-gii' => 
  array (
    'name' => 'yiisoft/yii2-gii',
    'version' => '2.2.4.0',
    'alias' => 
    array (
      '@yii/gii' => $vendorDir . '/yiisoft/yii2-gii/src',
    ),
  ),
  'yiisoft/yii2-symfonymailer' => 
  array (
    'name' => 'yiisoft/yii2-symfonymailer',
    'version' => 'dev-master',
    'alias' => 
    array (
      '@yii/symfonymailer' => $vendorDir . '/yiisoft/yii2-symfonymailer/src',
    ),
  ),
  'yiisoft/yii2-bootstrap4' => 
  array (
    'name' => 'yiisoft/yii2-bootstrap4',
    'version' => '2.0.10.0',
    'alias' => 
    array (
      '@yii/bootstrap4' => $vendorDir . '/yiisoft/yii2-bootstrap4/src',
    ),
  ),
  'hail812/yii2-adminlte-widgets' => 
  array (
    'name' => 'hail812/yii2-adminlte-widgets',
    'version' => '1.0.5.0',
    'alias' => 
    array (
      '@hail812/adminlte/widgets' => $vendorDir . '/hail812/yii2-adminlte-widgets/src',
    ),
  ),
  'hail812/yii2-adminlte3' => 
  array (
    'name' => 'hail812/yii2-adminlte3',
    'version' => 'dev-master',
    'alias' => 
    array (
      '@hail812/adminlte3' => $vendorDir . '/hail812/yii2-adminlte3/src',
    ),
  ),
  'yiisoft/yii2-bootstrap5' => 
  array (
    'name' => 'yiisoft/yii2-bootstrap5',
    'version' => 'dev-master',
    'alias' => 
    array (
      '@yii/bootstrap5' => $vendorDir . '/yiisoft/yii2-bootstrap5/src',
    ),
    'bootstrap' => 'yii\\bootstrap5\\i18n\\TranslationBootstrap',
  ),
);
