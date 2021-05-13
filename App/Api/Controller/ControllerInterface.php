<?php

namespace App\Api\Controller;

interface ControllerInterface
{
    public function execute (?array $params = null): void;    
}