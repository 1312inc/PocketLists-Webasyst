<?php

$im = new pocketlistsItemModel();

try {
    foreach ($im->getAll('id') as $id => $item) {
        $im->addCalculatedPriorityData($id, $item);
    }
} catch (waDbException $e) {
}
