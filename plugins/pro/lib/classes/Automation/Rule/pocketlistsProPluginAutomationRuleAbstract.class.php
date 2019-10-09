<?php

/**
 * Class pocketlistsProPluginAutomationRuleAbstract
 */
abstract class pocketlistsProPluginAutomationRuleAbstract implements pocketlistsProPluginAutomationRuleInterface, pocketlistsProPluginHtmlEditableInterface, pocketlistsProPluginSerializableInterface
{
    /**
     * @var pocketlistsProPluginAutomationRuleInterface
     */
    protected $nextRule;

    /**
     * @return string
     */
    public function getIdentifier()
    {
        return static::IDENTIFIER;
    }

    /**
     * @param pocketlistsProPluginAutomationRuleInterface $rule
     *
     * @return pocketlistsProPluginAutomationRuleInterface
     */
    public function setNext(pocketlistsProPluginAutomationRuleInterface $rule)
    {
        $this->nextRule = $rule;

        return $rule;
    }

    /**
     * @param $data
     *
     * @return bool
     */
    public function match($data)
    {
        if ($this->nextRule) {
            return $this->nextRule->match($data);
        }

        return false;
    }

    /**
     * @return bool
     */
    public function isEmpty()
    {
        return empty($this->getValue());
    }

    /**
     * @return string
     */
    public function __toString()
    {
        $json = json_encode($this, JSON_UNESCAPED_UNICODE);
        if ($json === false) {
            return '';
        }

        return $json;
    }

    /**
     * @param null  $selected
     * @param array $types
     *
     * @return string
     * @throws Exception
     */
    protected function getSelectCompareControl($selected = null, $types = [])
    {
        $options = [];

        foreach ($types ?: pocketlistsProPluginComparision::getTypes() as $item) {
            $options[] = [
                'title' => _wp($item),
                'value' => $item,
            ];
        }

        return waHtmlControl::getControl(
            waHtmlControl::SELECT,
            'data[rules]['.$this->getIdentifier().'][compare]',
            [
                'options' => $options,
                'value'   => $selected,
            ]
        );
    }

    /**
     * @return string
     * @throws Exception
     */
    protected function getHiddenIdentifierControl()
    {
        return waHtmlControl::getControl(
            waHtmlControl::HIDDEN,
            'data[rules]['.$this->getIdentifier().'][identifier]',
            ['value' => $this->getIdentifier()]
        );
    }

    /**
     * @param array  $data
     * @param string $mode
     *
     * @return string
     */
    protected function fetchTemplate(array $data = [], $mode = 'view')
    {
        $view = wa()->getView();
        $view->assign($data);

        return $view->fetch(
            wa()->getAppPath(
                sprintf('plugins/pro/templates/actions/automation/rule/%s.%s.html', $this->getIdentifier(), $mode)
            )
        );
    }
}
