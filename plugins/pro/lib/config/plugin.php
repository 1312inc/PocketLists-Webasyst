<?php
return array (
  'name' => 'Pro',
  'img' => 'img/pro.gif',
  'version' => '0.0.1',
  'vendor' => '--',
  'handlers' => 
  array (
      'backend_head' => 'backendHeadHandler',
      'backend_settings' => 'backendSettingsHandler',
      'backend_sidebar' => 'backendSidebarHandler',
      'backend_pocket' => 'backendPocketHandler',
      'backend_item_add' => 'backendItemAddHandler'
  ),
);
