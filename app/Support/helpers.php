<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;

function loadViewRoutes(string $namespace, string $path, array $exclude = []): void
{
    foreach (glob($path) as $page) {
        preg_match('/Views\/(.+?)\.blade\.php/', $page, $matches);
        if (isset($matches[1])) {
            if (in_array($matches[1], $exclude) || Str::contains($matches[1], 'layouts')) {
                continue;
            }
            $routePath = Str::endsWith($matches[1], '/index') ? Str::substr($matches[1], 0, -6) : $matches[1];
            $routeName = Str::replace('/', '.', $routePath);
            Route::view($routePath, $namespace.'::'.$matches[1])->name($routeName);
        }
    }
}

function buildCategoryTree(array &$elements, $parentId, string $parentIdField = 'parent_id', string $idField = 'id', string $childrenField = 'children'): array
{
    $branch = [];

    foreach ($elements as $element) {
        if ($element[$parentIdField] == $parentId) {
            $children = buildCategoryTree($elements, $element[$idField], $parentIdField, $idField, $childrenField);
            if ($children) {
                $element[$childrenField] = $children;
            }
            $branch[] = $element;
        }
    }

    return $branch;
}

function displayCategoryTree(array $categories, int $level = 0, string $nameField = 'name', string $childrenField = 'children'): void
{
    echo "<ul>\n";
    foreach ($categories as $category) {
        echo '<li>'.str_repeat('&nbsp;&nbsp;&nbsp;&nbsp;', $level); // Indentation
        echo htmlspecialchars($category[$nameField]);
        if (! empty($category[$childrenField])) {
            displayCategoryTree($category[$childrenField], $level + 1, $nameField, $childrenField);
        }
        echo "</li>\n";
    }
    echo "</ul>\n";
}
