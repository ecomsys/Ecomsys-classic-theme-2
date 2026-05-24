<?php
/**
 * Header Component (Stub)
 *
 * Простой адаптивный хедер-заглушка
 * 
 * <?php render_component('layouts.Header'); ?>
 */
?>

<header class="bg-white shadow-sm border-b border-gray-200 sticky top-0 z-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-16 md:h-20">
            
            <!-- Logo -->
            <div class="flex-shrink-0">
                <a href="/" class="flex items-center">
                    <div class="w-8 h-8 bg-blue-600 rounded-lg flex items-center justify-center">
                        <span class="text-white font-bold text-lg">L</span>
                    </div>
                    <span class="ml-2 text-xl font-semibold text-gray-900 hidden sm:inline">Logo</span>
                </a>
            </div>

            <!-- Desktop Navigation -->
            <nav class="hidden md:flex space-x-8">
                <a href="#" class="text-gray-700 hover:text-blue-600 transition-colors duration-200">Главная</a>
                <a href="#" class="text-gray-700 hover:text-blue-600 transition-colors duration-200">О нас</a>
                <a href="#" class="text-gray-700 hover:text-blue-600 transition-colors duration-200">Услуги</a>
                <a href="#" class="text-gray-700 hover:text-blue-600 transition-colors duration-200">Контакты</a>
            </nav>

            <!-- Mobile menu button -->
            <div class="md:hidden">
                <button type="button" class="text-gray-700 hover:text-blue-600 focus:outline-none">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                    </svg>
                </button>
            </div>

            <!-- Contact Button -->
            <div class="hidden md:block">
                <button class="bg-blue-600 text-white px-5 py-2 rounded-lg hover:bg-blue-700 transition-colors duration-200">
                    Связаться
                </button>
            </div>
        </div>
    </div>
</header>