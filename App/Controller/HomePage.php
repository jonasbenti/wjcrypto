<?php

namespace App\Controller;

use App\Helper\Helper;
use App\Api\Controller\ControllerInterface;

class HomePage implements ControllerInterface
{
    /** @var Helper $helper */
    private $helper;
    private $html;

    public function __construct(Helper $helper)
    {
        $this->helper = $helper;
        $this->html = file_get_contents('html/index.html');;
    }

    public function execute (?array $params = null): void
    {
        echo $this->html;
         $this->helper->redirect('https://www.google.com');
    }

}