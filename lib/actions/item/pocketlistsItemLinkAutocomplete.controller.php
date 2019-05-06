<?php

/**
 * Class pocketlistsItemLinkAutocompleteController
 */
class pocketlistsItemLinkAutocompleteController extends waJsonController
{
    /**
     * @throws waException
     */
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
