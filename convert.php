<?php

// ==================
// Конфигурация
// ==================
$config = [
    'rootDir' => __DIR__,

    'extraFiles' => [
        __DIR__ . '/tailwind.config.js'        
    ],

    'extensions' => ['html', 'css', 'scss', 'php'],

    'excludeDirs' => ['node_modules', '.git', 'dist'],

    'rootFontSize' => 16,

    'mode' => 'toRem', // 'toRem' | 'toPx'

    'dryRun' => false, // true = ничего не записывает
];

// ==================
// Сканирование файлов
// ==================
function scanFiles($rootDir, $extensions, $excludeDirs)
{
    $files = [];

    $directory = new RecursiveDirectoryIterator(
        $rootDir,
        FilesystemIterator::SKIP_DOTS
    );

    $filter = new RecursiveCallbackFilterIterator(
        $directory,
        function ($current) use ($excludeDirs) {
            if ($current->isDir()) {
                return !in_array($current->getFilename(), $excludeDirs);
            }
            return true;
        }
    );

    $iterator = new RecursiveIteratorIterator($filter);

    foreach ($iterator as $file) {
        if (!$file->isFile()) continue;

        $ext = strtolower(pathinfo($file->getFilename(), PATHINFO_EXTENSION));

        if (in_array($ext, $extensions)) {
            $files[] = $file->getPathname();
        }
    }

    return $files;
}

// ==================
// Конвертеры
// ==================
function pxToRem($content, $rootFontSize)
{
    return preg_replace_callback('/(-?\d+(\.\d+)?)px/', function ($m) use ($rootFontSize) {
        $value = (float)$m[1];
        return round($value / $rootFontSize, 4) . 'rem';
    }, $content);
}

function remToPx($content, $rootFontSize)
{
    return preg_replace_callback('/(-?\d+(\.\d+)?)rem/', function ($m) use ($rootFontSize) {
        $value = (float)$m[1];
        return round($value * $rootFontSize, 2) . 'px';
    }, $content);
}

// ==================
// Обработка файла
// ==================
function processFile($file, $config)
{
    $content = file_get_contents($file);

    if ($content === false) {
        echo "Ошибка чтения: $file\n";
        return;
    }

    $newContent = $config['mode'] === 'toRem'
        ? pxToRem($content, $config['rootFontSize'])
        : remToPx($content, $config['rootFontSize']);

    if ($newContent === $content) {
        return;
    }

    if ($config['dryRun']) {
        echo "[DRY] Изменился: $file\n";
        return;
    }

    if (file_put_contents($file, $newContent) !== false) {
        echo "✔ Обработан: $file\n";
    } else {
        echo "Ошибка записи: $file\n";
    }
}

// ==================
// Запуск
// ==================
$files = scanFiles(
    $config['rootDir'],
    $config['extensions'],
    $config['excludeDirs']
);

// добавляем одиночные файлы
foreach ($config['extraFiles'] as $file) {
    if (file_exists($file)) {
        $files[] = realpath($file);
    }
}

// убираем дубли
$files = array_unique($files);

// обработка
foreach ($files as $file) {
    processFile($file, $config);
}

echo "\nГотово! Режим: {$config['mode']}\n";