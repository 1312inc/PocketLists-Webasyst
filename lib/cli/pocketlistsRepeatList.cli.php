<?php

/**
 * Class pocketlistsRepeatListCli
 */
class pocketlistsRepeatListCli extends waCliController
{
    /**
     * @param $params
     * @return void
     * @throws waDbException
     * @throws waException
     */
    public function run($params = null)
    {
        $as_model = new waAppSettingsModel();
        $last_date = $as_model->get(pocketlistsHelper::APP_ID, 'last_repeat_list_cron_date');
        if ($last_date === date('Y-m-d')) {
            return;
        }

        try {
            if (pocketlistsRepetitions::repeatLists()) {
//                $as_model->set(pocketlistsHelper::APP_ID, 'last_repeat_list_cron_date', date('Y-m-d'));
            }
        } catch (Exception $ex) {
            pocketlistsLogger::error(
                sprintf(
                    'Error on repeating checklists. Error: %s. Trace: %s',
                    $ex->getMessage(),
                    $ex->getTraceAsString()
                ),
                'repeating.log'
            );
        }
echo "OK\n";
    }
}
