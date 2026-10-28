<?php

namespace App\Core;

class View
{
    public static function render(string $view, array $data = [], ?string $layout = 'main'): void
    {
        // Extract data variables to scope
        extract($data);

        // View path
        $viewFile = dirname(__DIR__) . '/Views/' . str_replace('.', '/', $view) . '.php';
        if (!file_exists($viewFile)) {
            die("View file [{$viewFile}] not found.");
        }

        // Buffer the view content
        ob_start();
        require $viewFile;
        $content = ob_get_clean();

        // If no layout requested, output directly
        if ($layout === null) {
            echo $content;
            return;
        }

        // Layout path
        $layoutFile = dirname(__DIR__) . '/Views/layouts/' . $layout . '.php';
        if (!file_exists($layoutFile)) {
            die("Layout file [{$layoutFile}] not found.");
        }

        require $layoutFile;
    }
}

