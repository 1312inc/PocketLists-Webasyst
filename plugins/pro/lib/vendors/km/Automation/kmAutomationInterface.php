<?php

/**
 * Interface kmAutomationInterface
 */
interface kmAutomationInterface
{
    /**
     * @return bool
     */
    public function checkRules();

    public function performAction();
}
