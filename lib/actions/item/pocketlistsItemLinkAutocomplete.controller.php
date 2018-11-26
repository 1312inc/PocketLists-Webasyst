<?php

/**
 * Class pocketlistsItemLinkAutocompleteController
 */
class pocketlistsItemLinkAutocompleteController extends waJsonController
{
    public function execute()
    {
        $term = waRequest::get('term', '');

        if (!$term) {
            return;
        }

        $result = (new pocketlistsItemLinkAutocompleter())->process($term)->getResult();

        $this->response = $result;
    }
}
