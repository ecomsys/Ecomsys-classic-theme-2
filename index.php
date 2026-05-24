<?php
    /**
 * The main template file
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Ecomsys Classic Theme
 */

    get_header();
?>
  <main class="flex-1 flex flex-col justify-end">

    <?php render_component('sections.Main'); ?>

    <?php
 render_component('ui.Slider', [
     'min'      => 0,
     'max'      => 50000,
     'step'     => 100,
     'value'    => [1000, 25000], // Массив включает режим 2 бегунков!
     'name'     => 'price',
     'prefix'   => '',
     'suffix'   => ' ₽',
     'showPins' => true,
     'pinAlways' => true //  Пины всегда на месте
 ]);
    ?>

    <button class="inline-block mx-auto" data-tooltip="Сохранить черновик" data-tooltip-position="top">
      Сохранить
    </button>

    <?php render_component('ui.Button', ['label' => 'Нажми меня']); ?>

    <!-- Вложенные компоненты через children -->
  <?php
      render_component('ui.Container', ['class' => 'flex items-center justify-center gap-2'],
          component('ui.Button', ['label' => 'Кнопка 1', 'onclick' => 'window.toast("Сохранено успешно!");']) .
          component('ui.Button', ['label' => 'Кнопка 2', 'onclick' => 'window.toast("Ошибка сети", {
                                                                                        type: "error",
                                                                                        description: "Проверьте подключениекинтернету"
                                                                                    })', ])
      );
  ?>

  <?php echo div(['class' => 'flex gap-2 justify-center'],
          button(['label' => 'Кнопка 1', 'data-tooltip' => 'Я всплывающая подсказка!', 'data-tooltip-position' => 'top'])
          . button(['label' => 'Кнопка 2', 'data-tooltip' => 'Я всплывающая подсказка!', 'data-tooltip-position' => 'top'])
  ) ?>


  </main>

<?php get_footer();
