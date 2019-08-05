<?php

$m = new pocketlistsModel();

$shortcuts = [
    [_wp('Call'), _wp('Remind'), _wp('Pay'), _wp('Ship'), _wp('Check'), _wp('Meet')],
    [_wp('today'), _wp('tomorrow'), _wp('in 2 days'), _wp('in a week'), _wp('in 2 weeks'), _wp('in a month')],
];

foreach ($shortcuts as $i => $names) {
    foreach ($names as $name) {
        $m->exec(
            sprintf("insert into pocketlists_pro_shortcut (name, `group`) values ('%s', %d)", $name, $i + 1)
        );
    }
}
