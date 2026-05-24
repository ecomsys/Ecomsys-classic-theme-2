<?php
// --------------------------------------------------------------------------------------------------------------------------------
// SVG SPRITE
// ---------------------------------------------------------------------------------------------------------------------------------
/**
 * Возвращает SVG иконку из спрайта темы.
 *
 * @param string $icon_id ID иконки в спрайте (например, 'burger')
 * @param string $classes CSS классы для SVG (Tailwind или свои)
 * @return void
 */
function svg_icon($icon_id, $classes = '')
{
    // Путь к спрайту темы
    $sprite_url = get_template_directory_uri() . '/assets/icons/sprite/sprite.svg';

    echo '<svg class="' . esc_attr($classes) . '">';
    echo '<use href="' . esc_url($sprite_url) . '#' . esc_attr($icon_id) . '"></use>';
    echo '</svg>';
}


