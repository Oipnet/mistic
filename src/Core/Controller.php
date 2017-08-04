<?php
/**
 * Created by PhpStorm.
 * User: arnaudp
 * Date: 01/08/17
 * Time: 08:25
 */

namespace Core;


abstract class Controller implements ControllerInterface
{

    public function render(): string {
        return 'render';
    }
}