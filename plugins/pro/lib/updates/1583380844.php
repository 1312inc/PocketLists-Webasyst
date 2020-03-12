<?php
$m = new waModel();

try {
    $m->exec('select * from pocketlists_pro_delayed_automation');
} catch (Exception $exception) {
    $m->exec(
        'create table pocketlists_pro_delayed_automation
(
	id int auto_increment,
	automation_id int not null,
	status smallint default 0 not null,
	event_data text not null,
	apply_datetime datetime not null,
	created_datetime datetime not null,
	executed_datetime datetime null,
	error text null,
	constraint pocketlists_pro_delayed_automation_pk
		primary key (id)
)
'
    );
}