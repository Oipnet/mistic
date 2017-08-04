<?php

namespace Core;

/**
 * Interface ControllerInterface
 * @package Core
 */
interface ControllerInterface
{
    /**
     * @return string
     */
    public function render(): string;
}
