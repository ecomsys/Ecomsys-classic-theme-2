```bash
ecomsys-classic/                          # Корень темы
│
│
├── cli_components/                              # Исходники CLI
│   ├── bin/                              # CLI бинарник
│   │   └── wp-components                 # Исполняемый файл
│   │
│   ├── CLI/
│   │   └── Commands/
│   │       ├── InitCommand.php       # Команда init
│   │       └── AddCommand.php        # Команда add
│   │
│   └── templates                     # Шаблоны компонентов 
│       ├── lib/
│       │    └── cn.js                # слияние классов tailwind
│       └── ui/
│           ├── Toast/                # Компонент Toast (Sonner)
│           │    ├── index.php        # файл с php
│           │    ├── style.css        # стили компонента (можно использовать @apply)
│           │    ├── script.js        # скрипт компонента es6      
│           │    ├── info.txt         # как использовать компонент
│           │    └── messages.json    # инструкция для пользователя при установке в терминале
│           │
│           ├── Conatiner/            # Компонент контейнер
│           ├── Fragment/             # Компонент фрагмент
│           └── ...
│
├── components/                       # Сюда попадают готовые компоненты (устанавливаются сюда)
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
│   ├── composer/ 
│   ├── gehrisandro/                  # tailwind-merge-php (только это)
│   │   └── tailwind-merge-php/
│   ├── psr/ 
│   ├── symfony                       # Symfony
│   └── autoload.php                  # Автолоадер CLI
│
├── functions.php                     # Главный файл темы
├── composer.json                     # Только для tailwind-merge
├── style.css                         # Стили темы
└── README.md                         # Инструкция
```