<?php


class kmAutomation
{
    private $rules   = [];
    private $actions = [];
    private $events  = [];

    public function addEvent($name, $rule)
    {
        if (!is_array($this->events[$name])) {
            $this->events[$name] = [];
        }

        $this->events[$name][] = $rule;
    }

    public function addRule()
    {

    }

    public function run(kmAutomationEventInterface $event)
    {
        foreach ($event->getAutomations() as $automation) {
            if ($automation->checkRules()) {
                $automation->performAction();
            }
        }
    }

    /**
     * @param string $name
     *
     * @return array
     */
    private function getEventRules($name)
    {
        return isset($this->events[$name]) ? $this->events[$name] : [];
    }
}
