<?php

$m = new waModel();

$m->query("INSERT INTO pocketlists_pocket (name) VALUES (s:name)", ['name' => wa()->accountName()]);

$m->exec(
    'UPDATE pocketlists_list set pocket_id = (select min(id) from pocketlists_pocket) where pocket_id=0'
);