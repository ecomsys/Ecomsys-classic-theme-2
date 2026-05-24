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
  console.error(' Не удалось создать POT через WP-CLI');
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

  console.log(` ${lang} PO и MO шаблоны созданы`);
});

console.log(' Все шаблоны для переводов готовы!');