<?php
/**
 * معالجات الاستعلامات (Query Handlers) - منفصل من functions.php
 *
 * @package Nadiim
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * تطبيق إعدادات Customizer على query الإصدارات
 *
 * @param WP_Query $query الاستعلام
 */
function nadiim_esdar_archive_query( $query ) {
    if ( ! is_admin() && $query->is_main_query() && is_post_type_archive( 'esdar' ) ) {
        // عدد الإصدارات في الصفحة
        $per_page = get_theme_mod( 'esdar_archive_per_page', 12 );
        $query->set( 'posts_per_page', $per_page );

        // ترتيب الإصدارات
        $orderby = get_theme_mod( 'esdar_archive_orderby', 'date' );
        $order = get_theme_mod( 'esdar_archive_order', 'DESC' );

        $query->set( 'orderby', $orderby );
        $query->set( 'order', $order );
    }
}
add_action( 'pre_get_posts', 'nadiim_esdar_archive_query' );

/**
 * تطبيق إعدادات query على أرشيف النوادي
 *
 * @param WP_Query $query الاستعلام
 */
function nadiim_reading_clubs_archive_query( $query ) {
    if ( ! is_admin() && $query->is_main_query() && is_post_type_archive( 'reading_clubs' ) ) {
        // فلترة النوادي العامة فقط
        $query->set( 'posts_per_page', 12 );
        $query->set( 'orderby', 'date' );
        $query->set( 'order', 'DESC' );
    }
}
add_action( 'pre_get_posts', 'nadiim_reading_clubs_archive_query' );

/**
 * تطبيق إعدادات query على أرشيف الحوارات
 *
 * @param WP_Query $query الاستعلام
 */
function nadiim_howarat_archive_query( $query ) {
    if ( ! is_admin() && $query->is_main_query() && is_post_type_archive( 'howarat' ) ) {
        $query->set( 'posts_per_page', 12 );
        $query->set( 'orderby', 'date' );
        $query->set( 'order', 'DESC' );
    }
}
add_action( 'pre_get_posts', 'nadiim_howarat_archive_query' );
