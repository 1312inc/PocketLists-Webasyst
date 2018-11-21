<?php

$m = new waModel();

$name = wa()->accountName() ? wa()->accountName() : _w('Pocket Lists');
$m->query("INSERT INTO pocketlists_pocket (name) VALUES (s:name)", ['name' => $name]);

$m->exec(
    'UPDATE pocketlists_list set pocket_id = (select min(id) from pocketlists_pocket) where pocket_id=0'
);
