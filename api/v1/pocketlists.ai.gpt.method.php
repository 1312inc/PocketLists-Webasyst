<?php

class pocketlistsAiGptMethod extends pocketlistsApiAbstractMethod
{
    public function execute()
    {
        $prompt = $this->get('prompt');

        $result = '';
        if ($prompt) {
            $result = (new pocketlistsApiAiRequest())->generate('todo_list', $prompt);
        }

        $this->response['data'] = ['text' =>  waUtils::jsonEncode($result)];
    }
}
