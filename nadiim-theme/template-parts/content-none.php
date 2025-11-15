<?php
/**
 * قالب عرض رسالة عدم وجود محتوى
 *
 * @package Nadiim
 * @since 1.0.0
 */
?>

<section class="no-results not-found">
    <header class="page-header">
        <h1 class="page-title"><?php esc_html_e( 'لا يوجد محتوى', 'nadiim' ); ?></h1>
    </header>

    <div class="page-content">
        <?php
        if ( is_home() && current_user_can( 'publish_posts' ) ) :

            printf(
                '<p>' . wp_kses(
                    __( 'هل أنت مستعد لنشر أول تدوينة؟ <a href="%1$s">ابدأ من هنا</a>.', 'nadiim' ),
                    array(
                        'a' => array(
                            'href' => array(),
                        ),
                    )
                ) . '</p>',
                esc_url( admin_url( 'post-new.php' ) )
            );

        elseif ( is_search() ) :
            ?>

            <p><?php esc_html_e( 'عذراً، لم نجد نتائج مطابقة لبحثك. حاول استخدام كلمات مختلفة.', 'nadiim' ); ?></p>
            <?php
            get_search_form();

        else :
            ?>

            <p><?php esc_html_e( 'يبدو أنه لا يمكننا العثور على ما تبحث عنه. ربما يمكنك المحاولة مرة أخرى باستخدام البحث.', 'nadiim' ); ?></p>
            <?php
            get_search_form();

        endif;
        ?>
    </div>
</section>
