<?php

$im = new pocketlistsItemModel();

try {
    foreach($im->getAll('id') as $id => $item) {
        $im->updateWithCalcPriority($id, $item);
    }
} catch (waDbException $e) {
}