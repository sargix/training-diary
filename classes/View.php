<?php

declare(strict_types=1);

namespace App;

class View
{
    public function renderView($page, $param = []): void
    {
        require_once("pages/layout.php");
    }
}
