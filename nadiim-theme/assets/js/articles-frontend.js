/**
 * Frontend JavaScript for Articles
 * Handles: Custom Audio Player, Reading Progress, Social Share
 *
 * @package Nadiim
 * @since 1.0.0
 */

(function($) {
    'use strict';

    /**
     * تهيئة عند تحميل الصفحة
     */
    $(document).ready(function() {
        initCustomAudioPlayer();
        initReadingProgress();
        initSocialShare();
        initSmoothScroll();
    });

    /**
     * مشغل الصوت المخصص
     */
    function initCustomAudioPlayer() {
        $('.nadiim-audio-player').each(function() {
            const $player = $(this);
            const audioElement = $player.find('audio').get(0);

            if (!audioElement) {
                console.log('Audio element not found');
                return;
            }

            const $playBtn = $player.find('.audio-play-btn');
            const $progress = $player.find('.audio-progress-bar');
            const $progressFill = $player.find('.audio-progress-fill');
            const $currentTime = $player.find('.audio-current-time');
            const $duration = $player.find('.audio-duration');
            const $volumeBtn = $player.find('.audio-volume-btn');
            const $speedBtn = $player.find('.audio-speed-btn');

            // تحميل البيانات الوصفية
            $(audioElement).on('loadedmetadata', function() {
                $duration.text(formatTime(this.duration));
            });

            // زر التشغيل/الإيقاف
            $playBtn.on('click', function() {
                if (audioElement.paused) {
                    audioElement.play();
                    $playBtn.addClass('playing');
                } else {
                    audioElement.pause();
                    $playBtn.removeClass('playing');
                }
            });

            // تحديث شريط التقدم
            $(audioElement).on('timeupdate', function() {
                if (this.duration > 0) {
                    const progress = (this.currentTime / this.duration) * 100;
                    $progressFill.css('width', progress + '%');
                    $currentTime.text(formatTime(this.currentTime));
                }
            });

            // عند انتهاء التشغيل
            $(audioElement).on('ended', function() {
                $playBtn.removeClass('playing');
                $progressFill.css('width', '0%');
            });

            // النقر على شريط التقدم
            $progress.on('click', function(e) {
                const clickX = e.pageX - $(this).offset().left;
                const width = $(this).width();
                const duration = audioElement.duration;
                audioElement.currentTime = (clickX / width) * duration;
            });

            // التحكم في الصوت
            if ($volumeBtn.length) {
                $volumeBtn.on('click', function() {
                    audioElement.muted = !audioElement.muted;
                    if (audioElement.muted) {
                        $(this).find('.volume-on-icon').hide();
                        $(this).find('.volume-off-icon').show();
                    } else {
                        $(this).find('.volume-on-icon').show();
                        $(this).find('.volume-off-icon').hide();
                    }
                });
            }

            // تغيير السرعة
            if ($speedBtn.length) {
                const speeds = [1, 1.25, 1.5, 1.75, 2];
                let currentSpeed = 0;

                $speedBtn.on('click', function() {
                    currentSpeed = (currentSpeed + 1) % speeds.length;
                    audioElement.playbackRate = speeds[currentSpeed];
                    $(this).text(speeds[currentSpeed] + 'x');
                });
            }
        });
    }

    /**
     * شريط تقدم القراءة
     */
    function initReadingProgress() {
        const $progressBar = $('.nadiim-reading-progress');
        if (!$progressBar.length) return;

        const $progressFill = $progressBar.find('.progress-fill');
        const $article = $('.article-content, .entry-content');

        if (!$article.length) return;

        $(window).on('scroll', function() {
            const scrollTop = $(window).scrollTop();
            const articleTop = $article.offset().top;
            const articleHeight = $article.outerHeight();
            const windowHeight = $(window).height();

            const scrolled = scrollTop - articleTop + windowHeight;
            const progress = Math.min(Math.max((scrolled / articleHeight) * 100, 0), 100);

            $progressFill.css('width', progress + '%');
        });
    }

    /**
     * المشاركة عبر وسائل التواصل
     */
    function initSocialShare() {
        $('.nadiim-share-btn').on('click', function(e) {
            e.preventDefault();

            const platform = $(this).data('platform');
            const url = encodeURIComponent(window.location.href);
            const title = encodeURIComponent(document.title);
            let shareUrl = '';

            switch(platform) {
                case 'twitter':
                    shareUrl = 'https://twitter.com/intent/tweet?url=' + url + '&text=' + title;
                    break;
                case 'facebook':
                    shareUrl = 'https://www.facebook.com/sharer/sharer.php?u=' + url;
                    break;
                case 'linkedin':
                    shareUrl = 'https://www.linkedin.com/sharing/share-offsite/?url=' + url;
                    break;
                case 'whatsapp':
                    shareUrl = 'https://api.whatsapp.com/send?text=' + title + ' ' + url;
                    break;
                case 'telegram':
                    shareUrl = 'https://t.me/share/url?url=' + url + '&text=' + title;
                    break;
                case 'email':
                    shareUrl = 'mailto:?subject=' + title + '&body=' + url;
                    break;
                case 'copy':
                    copyToClipboard(window.location.href);
                    showNotification('تم نسخ الرابط بنجاح!');
                    return;
            }

            if (shareUrl) {
                window.open(shareUrl, 'share', 'width=600,height=400');
            }
        });

        // Web Share API (للأجهزة التي تدعمها)
        if (navigator.share && $('.nadiim-native-share').length) {
            $('.nadiim-native-share').on('click', function(e) {
                e.preventDefault();

                navigator.share({
                    title: document.title,
                    text: $('meta[name="description"]').attr('content') || '',
                    url: window.location.href
            });
        }
    }

    /**
     * التمرير السلس
     */
    function initSmoothScroll() {
        $('a[href^="#"]').on('click', function(e) {
            const target = $(this.getAttribute('href'));

            if (target.length) {
                e.preventDefault();
                $('html, body').stop().animate({
                    scrollTop: target.offset().top - 80
                }, 600);
            }
        });
    }

    /**
     * تنسيق الوقت (ثواني إلى دقائق:ثواني)
     */
    function formatTime(seconds) {
        if (isNaN(seconds)) return '0:00';

        const mins = Math.floor(seconds / 60);
        const secs = Math.floor(seconds % 60);

        return mins + ':' + (secs < 10 ? '0' : '') + secs;
    }

    /**
     * الحصول على ID المقال الحالي
     */
    function getPostId() {
        return $('body').attr('class').match(/postid-(\d+)/)?.[1] || '0';
    }

    /**
     * نسخ النص إلى الحافظة
     */
    function copyToClipboard(text) {
        if (navigator.clipboard) {
            navigator.clipboard.writeText(text);
        } else {
            // Fallback للمتصفحات القديمة
            const $temp = $('<input>');
            $('body').append($temp);
            $temp.val(text).select();
            document.execCommand('copy');
            $temp.remove();
        }
    }

    /**
     * إظهار إشعار مؤقت
     */
    function showNotification(message, duration = 3000) {
        const $notification = $('<div class="nadiim-notification">' + message + '</div>');

        $('body').append($notification);

        setTimeout(function() {
            $notification.addClass('show');
        }, 100);

        setTimeout(function() {
            $notification.removeClass('show');
            setTimeout(function() {
                $notification.remove();
            }, 300);
        }, duration);
    }

    /**
     * إظهار إشعار للمشغل الصوتي مع خيارات
     */
    function showAudioNotification(message, onYes, onNo) {
        const $notification = $('<div class="nadiim-audio-notification">' +
            '<div class="notification-content">' +
                '<p>' + message + '</p>' +
                '<div class="notification-actions">' +
                    '<button class="btn-yes">نعم</button>' +
                    '<button class="btn-no">لا</button>' +
                '</div>' +
            '</div>' +
        '</div>');

        $('body').append($notification);

        $notification.find('.btn-yes').on('click', function() {
            if (onYes) onYes();
            $notification.remove();
        });

        $notification.find('.btn-no').on('click', function() {
            if (onNo) onNo();
            $notification.remove();
        });

        setTimeout(function() {
            $notification.addClass('show');
        }, 100);
    }

    /**
     * Lazy loading للصور
     */
    function initLazyLoading() {
        if ('IntersectionObserver' in window) {
            const imageObserver = new IntersectionObserver(function(entries, observer) {
                entries.forEach(function(entry) {
                    if (entry.isIntersecting) {
                        const img = entry.target;
                        img.src = img.dataset.src;
                        img.classList.remove('lazy');
                        imageObserver.unobserve(img);
                    }
                });
            });

            document.querySelectorAll('img.lazy').forEach(function(img) {
                imageObserver.observe(img);
            });
        }
    }

    // تهيئة إضافية
    initLazyLoading();

})(jQuery);
