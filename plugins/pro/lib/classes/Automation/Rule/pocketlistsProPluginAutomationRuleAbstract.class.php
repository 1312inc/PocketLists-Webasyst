<?php

/**
 * Class pocketlistsProPluginAutomationRuleAbstract
 *
 * Класс описывает условия правила, которые должны сработать, чтобы выполнилось действие
 */
abstract class pocketlistsProPluginAutomationRuleAbstract implements pocketlistsProPluginAutomationRuleInterface, pocketlistsProPluginHtmlEditableInterface, pocketlistsProPluginSerializableInterface
{
    /**
     * @var pocketlistsProPluginAutomationRuleInterface
     */
    protected $nextRule;

    /**
     * @var bool
     */
    protected $delayed = false;

    /**
     * @var bool
     */
    protected $skipDelayed = false;

    /**
     * @var mixed
     */
    protected $value;

    /**
     * @return string
     */
    public function getIdentifier()
    {
        return static::IDENTIFIER;
    }

    /**
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
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
     * @param bool $skipDelayed
     *
     * @return pocketlistsProPluginAutomationRuleAbstract
     */
    public function skipDelayed($skipDelayed)
    {
        $this->skipDelayed = $skipDelayed;

        return $this;
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
            'data[rules][' . $this->getIdentifier() . '][compare]',
            [
                'options' => $options,
                'value' => $selected,
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
            'data[rules][' . $this->getIdentifier() . '][identifier]',
            ['value' => $this->getIdentifier()]
        );
    }

    /**
     * @param array $options
     *
     * @return string
     */
    protected function getMultipleSelectControl($options)
    {
        return sprintf(
            '<select name="data[rules][%s][value][]" multiple class="pl-select-multiple">%s</select>',
            $this->getIdentifier(),
            array_reduce(
                $options,
                function ($options, $item) {
                    $selected = is_array($this->value) ? in_array($item['value'], $this->value) : false;
                    $options .= sprintf(
                        '<option value="%s" %s>%s</option>',
                        $item['value'],
                        $selected ? 'selected' : '',
                        $item['title']
                    );

                    return $options;
                },
                ''
            )
        );
    }

    /**
     * @return string
     * @throws Exception
     */
    protected function getDelayedCheckboxControl()
    {
        return waHtmlControl::getControl(
                waHtmlControl::HIDDEN,
                'data[rules][' . $this->getIdentifier() . '][delayed]',
                ['value' => '']
            )
            . '<br><span class="small">'
            . waHtmlControl::getControl(
                waHtmlControl::CHECKBOX,
                'data[rules][' . $this->getIdentifier() . '][delayed]',
                [
                    'title' => _wp(
                        'Delayed check (this check will be performed at the moment to-do is created rather than order action is performed; recommended only for to-dos created with a delay)'
                    ),
                    'value' => $this->delayed,
                ]
            )
            . '</span>';
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
                sprintf(
                    'plugins/pro/templates/actions%s/automation/rule/%s.%s.html',
                    pl2()->getUI2TemplatePath(),
                    $this->getIdentifier(),
                    $mode
                )
            )
        );
    }

    /**
     * @param array $json
     *
     * @return array
     */
    protected function loadValueAsArray($json)
    {
        if (!isset($json['value'])) {
            return [];
        }

        if (!empty($json['value']) && !is_array($json['value'])) {
            return [$json['value']];
        }

        if (empty($json['value'])) {
            return [];
        }

        return $json['value'];
    }
}
