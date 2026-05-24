<?php
// cli_components/CLI/Commands/InitCommand.php

namespace WPComponents\CLI\Commands;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Filesystem\Filesystem;

class InitCommand extends Command
{
    protected static $defaultName = 'init';
    
    protected function configure()
    {
        $this
            ->setName('init')
            ->setDescription('Инициализирует WP Components в теме WordPress')
            ->addOption('path', null, InputOption::VALUE_OPTIONAL, 'Путь к теме WordPress');
    }
    
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);
        $fs = new Filesystem();
        
        $io->title('WP Components - Инициализация');
        
        $pathOption = $input->getOption('path');
        if ($pathOption) {
            $themePath = rtrim($pathOption, '/\\');
            $io->text("Используем указанный путь: {$themePath}");
        } else {
            $themePath = getcwd();
            $io->text("Используем текущую директорию: {$themePath}");
        }
        
        if (!$fs->exists($themePath)) {
            $io->error("Путь '{$themePath}' не существует");
            return Command::FAILURE;
        }
        
        if (!$fs->exists($themePath . '/style.css')) {
            $io->warning("Похоже, это не папка темы WordPress (нет style.css)");
            $continue = $io->confirm('Продолжить все равно?', false);
            if (!$continue) {
                return Command::FAILURE;
            }
        }
        
        $componentsDir = $themePath . '/components/ui';
        if (!$fs->exists($componentsDir)) {
            $fs->mkdir($componentsDir);
            $io->success("✓ Создана папка: {$componentsDir}");
        }
        
        $configDir = $themePath . '/components';
        $configFile = $configDir . '/components.json';
        
        if (!$fs->exists($configDir)) $fs->mkdir($configDir);
        
        if (!$fs->exists($configFile)) {
            $config = [
                'name' => basename($themePath),
                'version' => '1.0.0',
                'components_path' => 'components/ui',
                'installed' => [],
                'prefix' => 'wp-',
                'created_at' => date('Y-m-d H:i:s')
            ];
            
            $fs->dumpFile($configFile, json_encode($config, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
            $io->success("✓ Создан конфиг: {$configFile}");
        }
        
        $incDir = $themePath . '/inc';
        $wpComponentsFile = $incDir . '/wp-components.php';
        
        if (!$fs->exists($incDir)) {
            $fs->mkdir($incDir);
            $io->success("✓ Создана папка: {$incDir}");
        }
        
        if (!$fs->exists($wpComponentsFile)) {
            $io->warning("Файл inc/wp-components.php не найден. Создайте его и подключите в functions.php");
        } else {
            $io->success("✓ Найден файл: {$wpComponentsFile}");
        }
        
        // Копируем папку утилит (lib)
        $templateBase = dirname(__DIR__, 2) . '/templates';
        $libSource = $templateBase . '/lib';
        $libTarget = $themePath . '/components/lib';

        if ($fs->exists($libSource) && !$fs->exists($libTarget)) {
            $fs->mirror($libSource, $libTarget);
            $io->success("✓ Скопирована папка утилит: components/lib");
        }

        // Копируем базовые компоненты
        $baseComponents = ['Container', 'Fragment'];

        foreach ($baseComponents as $comp) {
            $source = $templateBase . '/ui/' . $comp;
            $target = $componentsDir . '/' . $comp;
            
            if ($fs->exists($source) && !$fs->exists($target)) {
                $fs->mirror($source, $target);
                $io->success("✓ Скопирован базовый компонент: {$comp}");
            }
        }

        // ФИКС: ОБЯЗАТЕЛЬНО ЗАПИСЫВАЕМ БАЗОВЫЕ КОМПОНЕНТЫ В components.json
        if ($fs->exists($configFile)) {
            $config = json_decode(file_get_contents($configFile), true);
            if (!isset($config['installed'])) {
                $config['installed'] = [];
            }
            
            $configUpdated = false;
            foreach ($baseComponents as $comp) {
                if (!isset($config['installed'][$comp])) {
                    $config['installed'][$comp] = [
                        'installed_at' => date('Y-m-d H:i:s'),
                        'version' => '1.0.0',
                        'category' => 'ui'
                    ];
                    $configUpdated = true;
                }
            }
            
            if ($configUpdated) {
                file_put_contents($configFile, json_encode($config, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
                $io->success("✓ Базовые компоненты добавлены в конфиг");
            }
        }

        // Генерируем index.js для Vite
        $this->generateComponentsIndex($themePath, $io);
                
        $io->success([
            'Инициализация завершена! Установлены базовые компоненты Fragment и Container',           
            'Теперь можно устанавливать компоненты:',
            'composer wp/ui add Button',
            'composer wp/ui add Button Toast Alert',
            '==================================================================',
            'Внимание! Добавьте в ваш main.js (точка входа Vite):',
            "import './components/ui';",
            '==================================================================',
            'Использование в теме:',
            '<?php render_component("ui.Button", ["label" => "Click"]); ?>'
        ]);
        
        return Command::SUCCESS;
    }

    private function generateComponentsIndex(string $themePath, SymfonyStyle $io)
    {
        $fs = new Filesystem();
        $componentsDir = $themePath . '/components/ui';
        $indexFile = $componentsDir . '/index.js';

        if (!$fs->exists($componentsDir)) return;

        $exports = [];
        $dirs = glob($componentsDir . '/*', GLOB_ONLYDIR);

        foreach ($dirs as $dir) {
            $componentName = basename($dir);
            $scriptFile = $dir . '/script.js';
            if (file_exists($scriptFile)) {
                $exports[] = "export * from './{$componentName}/script.js';";
            }
        }

        $fs->dumpFile($indexFile, implode("\n", $exports) . "\n");
        $io->success("✓ Сгенерирован файл импортов: {$indexFile}");
    }
}