/**
 * Events Archive JavaScript
 * Handles filtering and searching of events
 *
 * @package Nadiim
 * @since 1.0.0
 */

(function($) {
    'use strict';

    $(document).ready(function() {
        initEventFilters();
    });

    /**
     * تهيئة نظام التصفية
     */
    function initEventFilters() {
        const $grid = $('#events-grid');
        const $filterStatus = $('#filter-status');
        const $filterType = $('#filter-type');
        const $filterOrder = $('#filter-order');
        const $filterSearch = $('#filter-search');
        const $resetBtn = $('#reset-filters');

        if (!$grid.length) return;

        /**
         * تطبيق التصفية
         */
        function applyFilters() {
            const statusFilter = $filterStatus.val();
            const typeFilter = $filterType.val();
            const searchQuery = $filterSearch.val().toLowerCase();
            const orderFilter = $filterOrder.val();

            let $cards = $grid.find('.event-card');

            // إخفاء جميع البطاقات أولاً
            $cards.hide();

            // تصفية حسب الحالة
            if (statusFilter) {
                $cards = $cards.filter('[data-status="' + statusFilter + '"]');
            }

            // تصفية حسب النوع
            if (typeFilter) {
                $cards = $cards.filter('[data-type="' + typeFilter + '"]');
            }

            // البحث النصي
            if (searchQuery) {
                $cards = $cards.filter(function() {
                    const title = $(this).find('.event-card-title').text().toLowerCase();
                    const excerpt = $(this).find('.event-card-excerpt').text().toLowerCase();
                    return title.indexOf(searchQuery) > -1 || excerpt.indexOf(searchQuery) > -1;
                });
            }

            // الترتيب
            if (orderFilter === 'desc') {
                $cards = $cards.get().reverse();
            }

            // إظهار البطاقات المفلترة
            $cards.fadeIn(300);

            // رسالة في حال عدم وجود نتائج
            if ($cards.length === 0) {
                if (!$grid.find('.no-results-message').length) {
                    $grid.append(`
                        <div class="no-results-message" style="grid-column: 1/-1; text-align: center; padding: 60px 20px;">
                            <div style="font-size: 48px; margin-bottom: 20px;">🔍</div>
                            <h3 style="margin-bottom: 10px; color: #1C2D27;">لا توجد نتائج</h3>
                            <p style="color: #6B7A72;">لم نعثر على فعاليات تطابق معايير البحث</p>
                        </div>
                    `);
                }
            } else {
                $grid.find('.no-results-message').remove();
            }
        }

        /**
         * إعادة تعيين التصفية
         */
        function resetFilters() {
            $filterStatus.val('');
            $filterType.val('');
            $filterOrder.val('asc');
            $filterSearch.val('');
            $grid.find('.event-card').show();
            $grid.find('.no-results-message').remove();
        }

        // الاستماع للتغييرات
        $filterStatus.on('change', applyFilters);
        $filterType.on('change', applyFilters);
        $filterOrder.on('change', applyFilters);
        $filterSearch.on('input', debounce(applyFilters, 300));
        $resetBtn.on('click', resetFilters);
    }

    /**
     * Debounce function للبحث
     */
    function debounce(func, wait) {
        let timeout;
        return function executedFunction(...args) {
            const later = () => {
                clearTimeout(timeout);
                func(...args);
            };
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
        };
    }

})(jQuery);
