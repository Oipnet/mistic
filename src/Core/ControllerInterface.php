<?php
/**
 * Created by PhpStorm.
 * User: arnaudp
 * Date: 01/08/17
 * Time: 08:28
 */

namespace Core;


interface ControllerInterface
{
    function render(): string;
}