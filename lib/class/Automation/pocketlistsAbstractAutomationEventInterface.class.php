<?php

/**
 * Interface pocketlistsAbstractAutomationEventInterface
 */
interface pocketlistsAbstractAutomationEventInterface
{
    /**
     * @return string
     */
    public function getName();

    /**
     * @return string
     */
    public function getType();

    public function applyAutomations();
}
