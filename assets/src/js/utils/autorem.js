// autorem.js

let globalScaleFactor = 1;

export function getScaleFactor(baseSiteWidth = 1536, baseFontSize = 16) {
  // Если ширина >= базовой — считаем динамически
  if (window.innerWidth >= baseSiteWidth) {
    return (window.innerWidth / baseSiteWidth);
  }
  // Иначе — 1 (адаптивный режим)
  return 1;
}

export default function initAutoRem({
  baseSiteWidth = 1536,
  baseFontSize = 16
} = {}) {
  const htmlElement = document.documentElement;

  function updateFontSize() {
    const screenWidth = window.innerWidth;
    const scaleFactor = screenWidth / baseSiteWidth;

    if (screenWidth >= baseSiteWidth) {
      globalScaleFactor = scaleFactor;
      const newFontSize = baseFontSize * scaleFactor;
      htmlElement.style.fontSize = `${newFontSize}px`;
      console.log("forced rem mode:", { screenWidth, baseSiteWidth, scaleFactor });
    } else {
      globalScaleFactor = 1;
      htmlElement.style.fontSize = "1rem";
      console.log("adaptive rem mode:", { screenWidth });
    }
  }

  window.addEventListener("resize", updateFontSize);
  updateFontSize(); // Первый вызов

  return {
    get scaleFactor() { return globalScaleFactor; }, // getter вместо значения
    destroy: () => window.removeEventListener("resize", updateFontSize)
  };
}