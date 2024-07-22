<?php

declare(strict_types=1);

namespace App\Application\Query;

use AllowDynamicProperties;

/**
 * @author Kamil Gąsior <kamilgasior07@gmail.com>
 */
#[AllowDynamicProperties] class GetCartQuery
{
    public function setView(array $view): void
    {
        $this->view = $view;
    }

    public function getView(): array
    {
        return $this->view;
    }
}