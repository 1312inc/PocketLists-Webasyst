<?php

class pocketlistsAiGptMethod extends pocketlistsApiAbstractMethod
{
    public function execute()
    {
        $prompt = $this->get('prompt');

        $result = '';
        if ($prompt) {
            $ai_request = (new pocketlistsApiAiRequest())->loadFieldsFromApi('pocketlists');

            $result = $ai_request->generate();
        }

        $this->response['data'] = ['text' =>  waUtils::jsonEncode($result)];
    }
}
