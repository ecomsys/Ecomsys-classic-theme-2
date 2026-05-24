<?php
/**
 * Footer Component (Stub)
 *
 * Простой адаптивный футер-заглушка
 * 
 * <?php render_component('layouts.Footer'); ?>
 */
?>

<footer class="bg-gray-900 text-gray-300">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <!-- TOP ROW -->
        <div class="grid grid-cols-1 md:grid-cols-12 gap-8 pb-8 border-b border-gray-700">
            
            <!-- COL 1 - LOGO -->
            <div class="md:col-span-4 text-center md:text-left">
                <a href="/" class="inline-block">
                    <div class="w-12 h-12 bg-blue-600 rounded-lg flex items-center justify-center mx-auto md:mx-0">
                        <span class="text-white font-bold text-xl">L</span>
                    </div>
                </a>
                <p class="mt-3 text-sm text-gray-400 max-w-xs mx-auto md:mx-0">
                    Ваш надежный партнер в мире цифровых решений
                </p>
            </div>

            <!-- COL 2 - CONTACTS -->
            <div class="md:col-span-5 space-y-4 text-center md:text-left">
                <div class="flex flex-col sm:flex-row justify-between gap-4">
                    <div class="flex items-center justify-center md:justify-start gap-2">
                        <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                        </svg>
                        <span class="text-sm">+7 (999) 123-45-67</span>
                    </div>
                    <div class="flex items-center justify-center md:justify-start gap-2">
                        <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                        </svg>
                        <span class="text-sm">info@example.com</span>
                    </div>
                </div>
                <div class="flex items-center justify-center md:justify-start gap-2">
                    <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>
                    <span class="text-sm">г. Москва, ул. Примерная, д. 123</span>
                </div>
            </div>

            <!-- COL 3 - BUTTON -->
            <div class="md:col-span-3 flex justify-center md:justify-end">
                <button class="w-full md:w-auto bg-blue-600 text-white px-6 py-2 rounded-full hover:bg-blue-700 transition duration-200 text-sm uppercase font-semibold">
                    Заказать звонок
                </button>
            </div>
        </div>

        <!-- BOTTOM ROW -->
        <div class="flex flex-col md:flex-row justify-between items-center gap-4 pt-8">
            <div class="text-sm text-gray-400 text-center md:text-left">
                © 2024 Все права защищены
            </div>
            <div class="flex gap-6 text-sm">
                <a href="#" class="text-gray-400 hover:text-blue-500 transition">Политика конфиденциальности</a>
                <a href="#" class="text-gray-400 hover:text-blue-500 transition">Условия использования</a>
            </div>
        </div>
    </div>
</footer>