<?php

$cs = new waContactSettingsModel();
$app_name = 'pocketlists';
$cs->set(wa()->getUser()->getId(), $app_name, 'app_icon', 2);
$cs->set(
    wa()->getUser()->getId(),
    $app_name,
    'email_me',
    json_encode(
        array(
            'assigns_me_item' => array('enable' => true),
            'creates_new_list' => array('enable' => true),
            'completes_item' => array('enable' => true, 'which' => 'favorite'),
            'comments_item' => array('enable' => true, 'which' => 'created'),
            'adds_item' => array('enable' => true, 'which' => 'any'),
        )
    )
);
$cs->set(
    wa()->getUser()->getId(),
    $app_name,
    'daily_recap',
    json_encode(
        array(
            'next' => array('enable' => true, 'which' => 'today tomorrow'),
        )
    )
);

$sm = new waAppSettingsModel();
$sm->deleteByField(array('app_id' => $app_name, 'name' => 'app_icon'));
$sm->deleteByField(array('app_id' => $app_name, 'name' => 'email_me'));
$sm->deleteByField(array('app_id' => $app_name, 'name' => 'daily_recap'));