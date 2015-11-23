<?php

$sm = new waAppSettingsModel();
$app_name = 'pocketlists';
$sm->set($app_name, 'app_icon', 2);
$sm->set(
    $app_name,
    'email_me',
    json_encode(array(
        'assigns_me_item' => array('enable' => true),
        'creates_new_list' => array('enable' => true),
        'completes_item' => array('enable' => true, 'which' => 'favorite'),
        'comments_item' => array('enable' => true, 'which' => 'created'),
        'adds_item' => array('enable' => true, 'which' => 'any'),
    ))
);
$sm->set(
    $app_name,
    'daily_recap',
    json_encode(array(
        'next' => array('enable' => true, 'which' => 'today tomorrow'),
    ))
);