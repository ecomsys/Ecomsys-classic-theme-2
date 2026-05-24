import fs from "fs-extra";
import path from "path";
import dotenv from "dotenv";

dotenv.config();

const rootDir = process.cwd();

// ---- Генерация блока favicon (ТОЛЬКО HTML ВСТАВКА) ----
const siteName = process.env.SITE_NAME || "Septic Classic Theme (Tailwind + Hot Reload)";
const shortName = process.env.SITE_SHORT_NAME || "Classic Theme";

const faviconLinks = `
<!-- Favicon и PWA icons -->
<link rel="apple-touch-icon" sizes="180x180" href="<?php echo get_template_directory_uri(); ?>/assets/favicons/apple-touch-icon.png">
<link rel="icon" type="image/png" sizes="32x32" href="<?php echo get_template_directory_uri(); ?>/assets/favicons/favicon-32x32.png">
<link rel="icon" type="image/png" sizes="16x16" href="<?php echo get_template_directory_uri(); ?>/assets/favicons/favicon-16x16.png">
<link rel="manifest" href="<?php echo get_template_directory_uri(); ?>/assets/favicons/site.webmanifest">
<meta name="msapplication-TileColor" content="#fefefe">
<meta name="theme-color" content="#ffffff">
<!-- /Favicon и PWA icons -->
`;

// ---- Обновление header.php ----
const headerFile = path.join(rootDir, "header.php");

if (await fs.pathExists(headerFile)) {
  let headerContent = await fs.readFile(headerFile, "utf-8");

  const faviconBlockRegex =
    /<!-- Favicon и PWA icons -->[\s\S]*?<!-- \/Favicon и PWA icons -->/i;

  if (faviconBlockRegex.test(headerContent)) {
    headerContent = headerContent.replace(faviconBlockRegex, faviconLinks.trim());
    console.log("♻ Блок фавиконок обновлён");
  } else {
    headerContent = headerContent.replace(
      /<\?php\s*wp_head\(\s*\);\s*\?>/i,
      `${faviconLinks}\n<?php wp_head(); ?>`
    );
    console.log("✔ Блок фавиконок добавлен");
  }

  await fs.writeFile(headerFile, headerContent, "utf-8");
} else {
  console.log("header.php не найден");
}

console.log("✔ Готово. Никакой генерации файлов больше нет.");