<?php
/**
 * Template part for displaying audio player
 *
 * @package Nadiim
 * @since 1.0.0
 */

$audio = nadiim_get_article_audio( get_the_ID() );

if ( ! $audio ) {
    return;
}
?>

<div class="nadiim-audio-player">
    <div class="audio-player-header">
        <svg class="audio-player-icon" viewBox="0 0 24 24" fill="currentColor">
            <path d="M12 3v10.55c-.59-.34-1.27-.55-2-.55-2.21 0-4 1.79-4 4s1.79 4 4 4 4-1.79 4-4V7h4V3h-6z"/>
        </svg>
        <span class="audio-player-title"><?php esc_html_e( 'استمع للمقال', 'nadiim' ); ?></span>
        <?php if ( ! empty( $audio['duration'] ) ) : ?>
            <span class="audio-duration-badge"><?php echo esc_html( $audio['duration'] ); ?></span>
        <?php endif; ?>
    </div>

    <audio preload="none" style="display: none;" crossorigin="anonymous">
        <source src="<?php echo esc_url( $audio['url'] ); ?>" type="audio/mpeg">
        <?php if ( ! empty( $audio['url'] ) && strpos( $audio['url'], '.ogg' ) !== false ) : ?>
            <source src="<?php echo esc_url( $audio['url'] ); ?>" type="audio/ogg">
        <?php endif; ?>
        <?php if ( ! empty( $audio['url'] ) && strpos( $audio['url'], '.wav' ) !== false ) : ?>
            <source src="<?php echo esc_url( $audio['url'] ); ?>" type="audio/wav">
        <?php endif; ?>
        <?php esc_html_e( 'متصفحك لا يدعم تشغيل الملفات الصوتية.', 'nadiim' ); ?>
    </audio>

    <div class="audio-player-controls">
        <button class="audio-play-btn" aria-label="تشغيل/إيقاف">
            <svg class="play-icon" width="20" height="20" viewBox="0 0 24 24" fill="white">
                <polygon points="5 3 19 12 5 21 5 3"></polygon>
            </svg>
            <svg class="pause-icon" width="20" height="20" viewBox="0 0 24 24" fill="white">
                <rect x="6" y="4" width="4" height="16"></rect>
                <rect x="14" y="4" width="4" height="16"></rect>
            </svg>
        </button>

        <div class="audio-progress-container">
            <div class="audio-progress-bar">
                <div class="audio-progress-fill"></div>
            </div>
            <div class="audio-time-display">
                <span class="audio-current-time">0:00</span>
                <span class="audio-duration">0:00</span>
            </div>
        </div>

        <div class="audio-additional-controls">
            <button class="audio-control-btn audio-volume-btn" aria-label="صوت">
                <svg class="volume-on-icon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <polygon points="11 5 6 9 2 9 2 15 6 15 11 19 11 5"></polygon>
                    <path d="M19.07 4.93a10 10 0 0 1 0 14.14M15.54 8.46a5 5 0 0 1 0 7.07"></path>
                </svg>
                <svg class="volume-off-icon" style="display: none;" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <polygon points="11 5 6 9 2 9 2 15 6 15 11 19 11 5"></polygon>
                    <line x1="23" y1="9" x2="17" y2="15"></line>
                    <line x1="17" y1="9" x2="23" y2="15"></line>
                </svg>
            </button>

            <button class="audio-control-btn audio-speed-btn" aria-label="السرعة">
                1x
            </button>

            <a href="<?php echo esc_url( $audio['url'] ); ?>" download class="audio-control-btn audio-download-btn" aria-label="تحميل">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                    <polyline points="7 10 12 15 17 10"></polyline>
                    <line x1="12" y1="15" x2="12" y2="3"></line>
                </svg>
            </a>
        </div>
    </div>

    <?php if ( ! empty( $audio['caption'] ) ) : ?>
        <div class="audio-caption">
            <?php echo esc_html( $audio['caption'] ); ?>
        </div>
    <?php endif; ?>
</div>

<style>
.audio-duration-badge {
    margin-right: auto;
    padding: 0.125rem 0.5rem;
    background: var(--color-primary, #339063);
    color: white;
    border-radius: 12px;
    font-size: 12px;
    font-weight: 600;
}

@media (max-width: 768px) {
    .audio-player-controls {
        flex-direction: column;
        gap: 1rem;
    }

    .audio-progress-container {
        width: 100%;
    }

    .audio-additional-controls {
        width: 100%;
        justify-content: space-around;
    }
}
</style>
