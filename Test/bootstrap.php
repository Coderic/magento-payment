<?php
declare(strict_types=1);

require __DIR__ . '/Stubs/MagentoStubs.php';
require dirname(__DIR__) . '/Model/Config.php';

spl_autoload_register(static function (string $class): void {
    $prefix = 'Wompi\\Payment\\Test\\';
    if (!str_starts_with($class, $prefix)) {
        return;
    }

    $relative = substr($class, strlen($prefix));
    $file = __DIR__ . '/' . str_replace('\\', '/', $relative) . '.php';
    if (is_file($file)) {
        require $file;
    }
});
