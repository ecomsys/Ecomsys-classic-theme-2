# WP-CLI глобально через XAMPP и инструменты для перевода WordPress

## 1. Скачиваем WP-CLI PHAR

Скачай файл:

https://raw.githubusercontent.com/wp-cli/builds/gh-pages/phar/wp-cli.phar


Сохрани в папку PHP XAMPP, например:

C:\xampp\php\wp-cli.phar


Теперь у тебя есть WP-CLI файл `wp-cli.phar`.

---

## 2. Создаём папку для глобальных bat-файлов

Открой CMD или PowerShell и выполни:

```cmd
mkdir C:\xampp-tools
```
В этой папке будут “глобальные” команды, чтобы Windows видел wp.

---
## 3. Создаём wp.bat
В CMD выполни:

```bash
echo @echo off > C:\xampp-tools\wp.bat
echo php "C:\xampp\php\wp-cli.phar" %* >> C:\xampp-tools\wp.bat
```
Проверяем:
```bash
type C:\xampp-tools\wp.bat
```

Должно быть ровно:
```bash
@echo off
php "C:\xampp\php\wp-cli.phar" %*
```

---
## 4. Добавляем C:\xampp-tools в PATH

В CMD:
```bash
setx PATH "%PATH%;C:\xampp-tools"
```

После этого закрой все окна терминала и открой новый, иначе Windows не увидит изменения PATH.
---
## 5. Проверяем WP-CLI

В новом терминале:
```bash
wp --info
```

Если видишь что-то вроде:

```bash
WP-CLI version: 2.x.x
PHP binary: ...
PHP version: ...
```

значит всё работает, wp теперь глобальная команда.
---

## 6. Включаем mbstring и intl для PHP CLI

Открой файл:

```bash
C:\php\php.ini
```

Найди строки:

```bash
;extension=mbstring
;extension=intl
```

Убери точку с запятой:

```bash
extension=mbstring
extension=intl
```

Сохрани файл.

Проверяем через терминал:

```bash
php -m | findstr mbstring
php -m | findstr intl
```

Если видишь mbstring и intl — расширения включены.

## 7. Тестируем WP-CLI
В новом терминале:

```bash 
wp --info
```

Если ошибок нет, WP-CLI готов к работе.

## 8. Скрипт генерации POT / PO / MO

Создай файл .npm/pot.js:

```js
import fs from 'fs';
import path from 'path';
import { execSync } from 'child_process';
import gettextParser from 'gettext-parser';

const domain = 'ecomsys-classic';
const themeDir = './';
const langDir = './languages';
const languages = ['ru_RU', 'en_US'];

if (!fs.existsSync(langDir)) fs.mkdirSync(langDir, { recursive: true });

const potFile = path.join(langDir, `${domain}.pot`);

console.log('Генерация POT через WP-CLI...');

try {
  execSync(`wp i18n make-pot ${themeDir} ${potFile} --domain=${domain}`, { stdio: 'inherit' });
} catch (e) {
  console.error('Не удалось создать POT через WP-CLI');
  process.exit(1);
}

console.log('POT создан:', potFile);

const potData = fs.readFileSync(potFile);

languages.forEach(lang => {
  const poPath = path.join(langDir, `${lang}.po`);
  const moPath = path.join(langDir, `${lang}.mo`);

  let po;
  if (fs.existsSync(poPath)) {
    po = gettextParser.po.parse(fs.readFileSync(poPath));
    const potParsed = gettextParser.po.parse(potData);
    po.translations = potParsed.translations;
  } else {
    po = gettextParser.po.parse(potData);
  }

  po.headers.Language = lang;
  po.headers['Content-Type'] = 'text/plain; charset=UTF-8';

  fs.writeFileSync(poPath, gettextParser.po.compile(po));
  fs.writeFileSync(moPath, gettextParser.mo.compile(po));

  console.log(`${lang} PO и MO созданы`);
});

console.log('Все переводы готовы!');
```

## 9. Добавляем скрипт в package.json

```json
"scripts": {
  "pot": "node .npm/pot.js"
}
```

## 10. Запуск скрипта

В терминале:

```bash
npm run pot
```

POT → languages/classic.pot

PO → languages/ru_RU.po и languages/en_US.po

MO → languages/ru_RU.mo и languages/en_US.mo

Существующие переводы обновляются автоматически, ничего не теряется.