<?php return array (
  'package' => 
  array (
    'type' => 'module',
    'name' => 'adcustommodule',
    'version' => '4.9.0',
    'path' => 'application/modules/Adcustommodule',
    'title' => 'Ad Custom Module',
    'description' => 'This is custom module developed by Anil for custom work.',
    'author' => 'Anil',
    'callback' => 
    array (
      'class' => 'Engine_Package_Installer_Module',
    ),
    'actions' => 
    array (
      0 => 'install',
      1 => 'upgrade',
      2 => 'refresh',
      3 => 'enable',
      4 => 'disable',
    ),
    'directories' => 
    array (
      0 => 'application/modules/Adcustommodule',
    ),
  ),
); ?>