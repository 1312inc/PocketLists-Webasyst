<?php

$m = new pocketlistsModel();

$labels = [
    ['Backlog', 'aaaaaa'],
    ['To do', '46c7fc'],
    ['In progress', '328532'],
    ['Testing', 'ffc600'],
];

foreach ($labels as $label) {
    $m->exec(
        sprintf("insert into pocketlists_pro_label (name, color, sort) values ('%s', '%s', null)", $label[0], $label[1]));
}
