<?php

namespace Gounsch;

class View
{
    public function __construct(private ?string $user)
    {
    }

    public function render(string $template, array $vars): string
    {
        if (!file_exists($template)) {
            throw new \InvalidArgumentException("Template '{$template}' not found.");
        }

        if (null === $this->user) {
            $vars['action'] = 'login';
        }

        // VULN 2: Unsafe extraction of potentially user passed input into php variables
        // Allowing user to choose which template to execute at will like http://127.0.0.1:9876/?template=../templates/edit.php
        extract($vars);

        ob_start();

        include $template;

        return ob_get_clean();
    }
}