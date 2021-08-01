<?php

/**
 * Class pocketlistsProPluginSettingsActions
 */
class pocketlistsProPluginSettingsActions extends pocketlistsViewActions
{
    public function labelsAction()
    {
        $labels = pl2()->getModel(pocketlistsProPluginLabel::class)->getAllWithSort();
        $this->view->assign(
            [
                'labels_json' => json_encode(array_reverse($labels), JSON_UNESCAPED_UNICODE),
            ]
        );
    }

    /**
     * @throws waException
     */
    public function shopscriptAction()
    {
        $automation = pocketlistsProPlugin::getInstance()->getAutomationService();

        /** @var pocketlistsProPluginAutomationSettingsDto[] $shopActions */
        $shopActions = $automation->getAvailableEventsForGroup();

        /** @var pocketlistsProPluginAutomationFactory $f */
        $f = pl2()->getEntityFactory(pocketlistsProPluginAutomation::class);
        /** @var pocketlistsProPluginAutomation[] $automations */
        $automations = $f->findByEventAndType(pocketlistsProPluginAutomationShopOrderActionEvent::NAME, 'shop');

        foreach ($automations as $automation) {
            if (!$automation->isValid()) {
                pocketlistsLogger::debug(sprintf('Automation %s is not valid, skip', $automation->getId()));

                continue;
            }

            try {
                $actionId = '';
                $rules = [];

                foreach ($automation->getRules() as $rule) {
                    if ($rule instanceof pocketlistsProPluginAutomationRuleShopAction) {
                        $actionId = $rule->getValue()->getId();
                        $shopActions['shop.' . $actionId]->automations[] = $automation;
                    } else {
                        $rules[] = $rule->viewHtml();
                    }
                }

                $shopActions['shop.' . $actionId]->automationRulesHtml[$automation->getId()] = implode(
                    _wp(' AND '),
                    array_filter(
                        $rules,
                        static function ($a) {
                            return !empty($a);
                        }
                    )
                );

                if (empty($shopActions['shop.' . $actionId]->automationRulesHtml[$automation->getId()])) {
                    $shopActions['shop.' . $actionId]->automationRulesHtml[$automation->getId()] = _wp('ALL');
                }

                $shopActions['shop.' . $actionId]->automationActionsHtml[$automation->getId()] = $automation->getAction()
                    ->viewHtml();
            } catch (pocketlistsProPluginNoShopActionException $exception) {
                pocketlistsLogger::debug($exception->getMessage());
            } catch (Exception $exception) {
                pocketlistsLogger::error($exception->getMessage());
            }
        }

        $this->view->assign(['shopActions' => $shopActions]);
    }

    public function shortcutsAction()
    {
        $shortcuts = pl2()->getModel(pocketlistsProPluginShortcut::class)->getAllGrouped();
        $this->view->assign(
            [
                'shortcuts_json' => json_encode($shortcuts, JSON_UNESCAPED_UNICODE),
            ]
        );
    }
}
