# РАЗРАБОТКА

## Установи зависимости npm

```bash
npm i -D
```


## Добавь в .env путь

Пример:

```bash
SITE_URL=http://localhost/wp              # подобный путь если через apache, nginx (норма)
```

---

## Если вордпресс из DOCKER ?

выполни:

```bash
docker ps
```

## посмотреть колонку PORTS.

Например:

```bash
0.0.0.0:8080->80/tcp
```

Значит WordPress открыт на:

```bash
http://localhost:8080                  # такой путь если docker (открываем просто порт)
```
в .env вводи:
```bash
SITE_URL=http://localhost:8000    
```


# Разработка компонентов

```bash
ecomsys/                              # Корень темы
│
├── bin/                              # CLI бинарник
│   └── wp-components                 # Исполняемый файл
│
├── src/                              # Исходники CLI
│   └── CLI/
│       └── Commands/
│           ├── InitCommand.php       # Команда init
│           └── AddCommand.php        # Команда add
│
├── components/                       # Компоненты (устанавливаются сюда)
│   └── ui/
│       └── Button/
│           ├── render
│           │   └── index.php         # файл рендера
│           └── messages.json         # информация об использовании 
│           
├── inc/                              # Вспомогательные файлы темы
│   └── components.php                # Функции cn() и render_component()
│
├── vendor/                           # Composer зависимости
│   ├── gehrisandro/                  # tailwind-merge-php (только это)
│   │   └── tailwind-merge-php/
│   ├── symfony                       # Symfony
│   └── autoload.php                  # Автолоадер CLI
│
├── wp-components                     # Скрипт запуска CLI (в корне)
├── functions.php                     # Главный файл темы
├── composer.json                     # Только для tailwind-merge
├── style.css                         # Стили темы
└── README.md                         # Инструкция
```


## 1. Установи зависимости Composer
```bash
composer install
```

## 2. Инициализируй библиотеку
```bash
php wp-components init
```

## 3. Добавь компонент
```bash
php wp-components add Button
```


# Inject SVG

```php
<?php svg_icon('burger', 'w-6 h-6 fill-current text-blue-500'); ?>
```
