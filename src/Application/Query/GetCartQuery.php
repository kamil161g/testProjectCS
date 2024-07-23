<?php

declare(strict_types=1);

namespace App\Application\Query;

/**
 * @author Kamil Gąsior <kamilgasior07@gmail.com>
 */
readonly class GetCartQuery
{
    private array $view;

    public function setView(array $view): void
    {
        $this->view = $view;
    }

    public function getView(): array
    {
        return $this->view;
    }
}