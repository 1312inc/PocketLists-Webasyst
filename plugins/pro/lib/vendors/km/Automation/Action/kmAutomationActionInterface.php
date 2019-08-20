<?php

/**
 * Interface kmAutomationActionInterface
 */
interface kmAutomationActionInterface extends JsonSerializable, kmUnserializableInterface, kmHtmlEditableInterface
{
    /**
     * @return string
     */
    public function getName();

    /**
     * @return mixed
     */
    public function perform();
}
