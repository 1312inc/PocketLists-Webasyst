<?php
// pocketsbye update
waFiles::delete(wa()->getAppPath('lib/model/pocketlistsPocket.model.php', 'pocketlists'));
waFiles::delete(wa()->getAppPath('lib/model/pocketlistsPocketRights.model.php', 'pocketlists'));
waFiles::delete(wa()->getAppPath('templates/actions/pocket', 'pocketlists'), true);
waFiles::delete(wa()->getAppPath('lib/actions/pocket', 'pocketlists'), true);
