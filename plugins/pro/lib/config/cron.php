<?php

return [
    'apply_delayed_automations' => sprintf(
        '*/10 * * * * /usr/bin/php -q %s%scli.php pocketlists proPluginApplyDelayedAutomations',
        wa()->getConfig()->getPath('root'),
        DIRECTORY_SEPARATOR
    ),
];
