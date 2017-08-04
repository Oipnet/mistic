<?php

namespace Core;

/**
 * Class Controller
 * @package Core
 */
abstract class Controller implements ControllerInterface
{

    /**
     * @return string
     */
    public function render(): string
    {
        return 'render';
    }
}
