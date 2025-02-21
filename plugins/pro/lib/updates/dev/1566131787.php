<?php

$m = new pocketlistsModel();

try {
    $m->exec('select * from pocketlists_pro_automation_rules');
} catch (waException $ex) {
    $m->exec(
        'create table pocketlists_pro_automation_rules
            (
                id int auto_increment,
                conditions text not null,
                created_by int not null,
                created_datetime datetime null,
                updated_datetime datetime null,
                constraint pocketlists_pro_automation_rules_pk
                    primary key (id)
            )'
    );
}

try {
    $m->exec('select * from pocketlists_pro_automation');
} catch (waException $ex) {
    $m->exec(
        "create table pocketlists_pro_automation
            (
                id int auto_increment,
                event text not null,
                rules text not null,
                action text not null,
                type enum('shop') not null,
                created_by int not null,
                created_datetime datetime null,
                updated_datetime datetime null,
                constraint pocketlists_pro_automation_pk
                    primary key (id)
            )"
    );
}
