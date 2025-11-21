<?php
/**
 * Main Template File
 *
 * @package Madjaliss
 * @version 2.0
 */

get_header();
?>

<main id="primary" class="site-main">
    <div class="container">

        <?php if (have_posts()) : ?>

            <div class="posts-grid">

                <?php
                while (have_posts()) :
                    the_post();

                    // يمكن إضافة template part للمقالات لاحقاً
                    ?>
                    <article id="post-<?php the_ID(); ?>" <?php post_class('post-item'); ?>>

                        <?php if (has_post_thumbnail()) : ?>
                            <div class="post-thumbnail">
                                <a href="<?php the_permalink(); ?>">
                                    <?php the_post_thumbnail('large'); ?>
                                </a>
                            </div>
                        <?php endif; ?>

                        <div class="post-content">
                            <header class="entry-header">
                                <?php the_title('<h2 class="entry-title"><a href="' . esc_url(get_permalink()) . '">', '</a></h2>'); ?>

                                <div class="entry-meta">
                                    <span class="posted-on">
                                        <time datetime="<?php echo esc_attr(get_the_date('c')); ?>">
                                            <?php echo esc_html(get_the_date()); ?>
                                        </time>
                                    </span>
                                </div>
                            </header>

                            <div class="entry-summary">
                                <?php the_excerpt(); ?>
                            </div>

                            <footer class="entry-footer">
                                <a href="<?php the_permalink(); ?>" class="read-more">
                                    <?php esc_html_e('اقرأ المزيد', 'madjaliss'); ?>
                                </a>
                            </footer>
                        </div>

                    </article>
                    <?php
                endwhile;
                ?>

            </div>

            <?php
            // Pagination
            the_posts_pagination(array(
                'mid_size'  => 2,
                'prev_text' => __('&rarr; السابق', 'madjaliss'),
                'next_text' => __('التالي &larr;', 'madjaliss'),
            ));
            ?>

        <?php else : ?>

            <div class="no-posts">
                <h2><?php esc_html_e('لا توجد مقالات', 'madjaliss'); ?></h2>
                <p><?php esc_html_e('لم يتم العثور على أي محتوى.', 'madjaliss'); ?></p>
            </div>

        <?php endif; ?>

    </div>
</main>

<?php
get_footer();
