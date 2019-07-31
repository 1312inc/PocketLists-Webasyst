<?php
return array(
    'pocketlists_pro_label' => array(
        'id' => array('int', 11, 'null' => 0, 'autoincrement' => 1),
        'name' => array('mediumtext'),
        'color' => array('varchar', 6, 'null' => 0),
        'sort' => array('mediumint', 9),
        ':keys' => array(
            'PRIMARY' => 'id',
        ),
    ),
);
