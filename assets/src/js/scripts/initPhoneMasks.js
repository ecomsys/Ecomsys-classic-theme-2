import IMask from "imask";

export function initPhoneMasks() {
  const inputs = document.querySelectorAll(
    '[type="tel"]',
  );

  inputs.forEach((input) => {
    const mask = IMask(input, {
      mask: "+{7} 000 000-00-00", // Россия
    });

    // Добавляем валидацию при вводе
    input.addEventListener("blur", function () {
      const value = mask.unmaskedValue; // получаем только цифры

      // Проверяем что номер полный (11 цифр после +7)
      if (value.length > 0 && value.length !== 11) {      
        this.style.backgroundColor = "#fef2f2";
        this.setCustomValidity(
          "Введите полный номер телефона (10 цифр после +7)",
        );
      } else if (value.length === 11) {        
        this.style.backgroundColor = "#ffffff";
        this.setCustomValidity("");
      } else {        
        this.style.backgroundColor = "";
        this.setCustomValidity("");
      }
    });

    // Очищаем стили при фокусе
    input.addEventListener("focus", function () {      
      this.style.backgroundColor = "";
      this.setCustomValidity("");
    });
  });
}
