<?php

/**
 * Class pocketlistsSettingsShopscriptAction
 */
class pocketlistsSettingsShopscriptAction extends pocketlistsViewAction
{
    public function runAction($params = null)
    {
        $automation = pl2()->getAutomationService();
        $shopActions = $automation->getAvailableEventsForGroup();
        $deletedAction = end($shopActions);

        /** @var pocketlistsAutomationFactory $f */
        $f = pl2()->getEntityFactory(pocketlistsAutomation::class);
        $automations = $f->findByEventAndType(pocketlistsAutomationShopOrderActionEvent::NAME, 'shop');
        foreach ($automations as $automation) {
            if (!$automation->isValid()) {
                pocketlistsLogger::debug(sprintf('Automation %s is not valid, skip', $automation->getId()));

                continue;
            }

            try {
                $actionId = '';
                $rules = [];
                foreach ($automation->getRules() as $rule) {
                    if ($rule instanceof pocketlistsAutomationRuleShopAction) {
                        $actionId = $rule->getValue()->getId();
                    } else {
                        $rules[] = $rule->viewHtml();
                    }
                }

                if (!$actionId) {
                    $actionId = $deletedAction->id;
                }

                $actionId = 'shop.'.$actionId;
                if (!empty($shopActions[$actionId]))
                {
                    $shopActions[$actionId]->automations[] = $automation;

                    $shopActions[$actionId]->automationRulesHtml[$automation->getId()] = implode(
                        _wp(' AND '),
                        array_filter(
                            $rules,
                            static function ($a) {
                                return !empty($a);
                            }
                        )
                    );

                    if (empty($shopActions[$actionId]->automationRulesHtml[$automation->getId()])) {
                        $shopActions[$actionId]->automationRulesHtml[$automation->getId()] = _wp('ALL');
                    }

                    $shopActions[$actionId]->automationActionsHtml[$automation->getId()] = $automation->getAction()->viewHtml();
                }
            } catch (pocketlistsNoShopActionException $exception) {
                pocketlistsLogger::debug($exception->getMessage());
            } catch (Exception $exception) {
                pocketlistsLogger::error($exception->getMessage());
            }

            // не показывать когда нет правил
            // if (empty($shopActions['shop.'.$deletedAction->id]->automations)) {
            //     unset($shopActions['shop.'.$deletedAction->id]);
            // }
        }

        $this->view->assign([
            'shopActions'     => $shopActions,
            'deletedActionId' => $deletedAction->id,
            'settings'        => (new pocketlistsSettings())->getSettings(),
        ]);
    }
}
