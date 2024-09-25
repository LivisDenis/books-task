<?php

namespace App\Kernel\View;

use App\Kernel\Exceptions\ViewNotFoundException;
use App\Kernel\Session\SessionInterface;

class View implements ViewInterface
{
    public function __construct(private SessionInterface $session)
    {}
    public function page(string $name, array $data): void
    {
        $page = APP_PATH."/views/pages/$name.php";

        if (!file_exists($page)) {
            throw new ViewNotFoundException("Page $name not found");
        }

        extract(array_merge($this->defaultData(), $data));

        include_once $page;
    }

    public function component($name): void
    {
        $component = APP_PATH."/views/components/$name.php";

        if (!file_exists($component)) {
            echo "Component $name not found";
            return;
        }

        include_once $component;
    }

    private function defaultData(): array
    {
        return [
            'view' => $this,
            'session' => $this->session
        ];
    }
}