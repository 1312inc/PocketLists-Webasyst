<?php

$m = new pocketlistsModel();

$labels = [
    [_wp('Backlog'), 'aaaaaa'],
    [_wp('To do'), '46c7fc'],
    [_wp('In progress'), '328532'],
    [_wp('Testing'), 'ffc600'],
];

foreach ($labels as $label) {
    $m->exec(
        sprintf("insert into pocketlists_pro_label (name, color, sort) values ('%s', '%s', null)", $label[0], $label[1]));
}
