<?php

namespace Core;

abstract class Controller implements ControllerInterface
{

    public function render(): string
    {
        return 'render';
    }
}
