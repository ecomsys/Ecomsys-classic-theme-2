<?php
// cli_components/CLI/Commands/AddCommand.php

namespace WPComponents\CLI\Commands;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Filesystem\Filesystem;

class AddCommand extends Command
{
    protected static $defaultName = 'add';
    
    protected function configure()
    {
        $this
            ->setDescription('Добавляет один или несколько компонентов в WordPress тему')
            // IS_ARRAY позволяет писать: add Button Toast Alert
            ->addArgument('component', InputArgument::IS_ARRAY, 'Название компонентов (Button Toast Card)');
    }
    
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);
        $fs = new Filesystem();
        
        $componentNames = $input->getArgument('component');
        
        if (empty($componentNames)) {
            $io->error('Укажите хотя бы один компонент для установки. Пример: composer wp/ui add Button Toast');
            return Command::FAILURE;
        }
        
        $io->title('Установка компонентов');
        
        $themePath = $this->findThemePath($io);
        if (!$themePath) {
            $io->error('Сначала запустите composer wp/ui init в папке темы');
            return Command::FAILURE;
        }
        
        $io->text("Тема найдена: {$themePath}");
        
        $configFile = $themePath . '/components/components.json';
        
        // Проходимся по всем переданным компонентам
        foreach ($componentNames as $componentName) {
            $io->section("Обработка: {$componentName}");
            
            $templatePath = dirname(__DIR__, 2) . '/templates/ui/' . $componentName;
            
            if (!$fs->exists($templatePath)) {
                $io->error("Компонент '{$componentName}' не найден в библиотеке");
                continue; // Продолжаем установку остальных
            }
            
            $targetPath = $themePath . '/components/ui/' . $componentName;
            
            // Защита от перезаписи (чтобы не затереть кастомные правки юзера)
            if ($fs->exists($targetPath)) {
                $io->warning("Компонент '{$componentName}' уже установлен. Пропускаем.");
                continue;
            }
            
            $fs->mkdir($targetPath);
            $fs->mirror($templatePath, $targetPath);
            $io->success("✓ Компонент скопирован в: {$targetPath}");
            
            // Обновляем конфиг
            if ($fs->exists($configFile)) {
                $config = json_decode(file_get_contents($configFile), true);
                if (!isset($config['installed'])) {
                    $config['installed'] = [];
                }
                
                $config['installed'][$componentName] = [
                    'installed_at' => date('Y-m-d H:i:s'),
                    'version' => '1.0.0',
                    'category' => 'ui'
                ];
                
                file_put_contents($configFile, json_encode($config, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
                $io->success("✓ Обновлен конфиг: {$configFile}");
            }
            
            // Выводим сообщения
            $messagesFile = $templatePath . '/messages.json';
            if ($fs->exists($messagesFile)) {
                $messages = json_decode(file_get_contents($messagesFile), true);
                if (!empty($messages)) {
                    $io->success($messages);
                }
            }
        }

        // Генерируем index.js для Vite (один раз после всех установок)
        $this->generateComponentsIndex($themePath, $io);
        
        return Command::SUCCESS;
    }
    
    private function findThemePath(SymfonyStyle $io)
    {
        $currentDir = getcwd();
        $dir = $currentDir;
        
        for ($i = 0; $i < 10; $i++) {
            if (file_exists($dir . '/components/components.json')) {
                return $dir;
            }          
            $dir = dirname($dir);
            if ($dir === '/' || $dir === '\\' || $dir === '.') {
                break;
            }
        }
        
        $manualPath = $io->ask('Введите путь к вашей теме WordPress', null);
        
        if ($manualPath && file_exists($manualPath)) {
            return rtrim($manualPath, '/\\');
        }
        
        return null;
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
        $io->success("✓ Обновлен файл импортов: {$indexFile}");
    }
}