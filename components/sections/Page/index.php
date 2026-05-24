<?php
    /**
 *
 * Рендер через render_component('Sections.Page')
 */
?>

<section class="bg-gradient-to-br from-blue-50 to-white py-16 md:py-24">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <?php
        while (have_posts()):
            the_post();
    ?>
        <div class="flex flex-col gap-4 sm:gap-10">
            <h1><?php the_title()?></h1>

            <div class="px-5 sm:px-20"><?php the_content()?></div>
        </div>

        <?php
                endwhile; // End of the loop.
        ?>
</div>
</section>