<?php
/**
 * Template Name: من نحن
 * Template Post Type: page
 *
 * @package Nadiim
 * @since 1.0.0
 */

get_header();
?>

<main id="primary" class="site-main page-about">
    <div class="container container-narrow">

        <?php while ( have_posts() ) : the_post(); ?>

            <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

                <!-- Hero Section -->
                <header class="page-header" style="text-align: center; padding: var(--spacing-xxl) 0; margin-bottom: var(--spacing-xl);">
                    <?php if ( has_post_thumbnail() ) : ?>
                        <div style="width: 150px; height: 150px; margin: 0 auto var(--spacing-lg); border-radius: 50%; overflow: hidden; border: 5px solid var(--color-primary); box-shadow: 0 10px 30px rgba(0,0,0,0.1);">
                            <?php the_post_thumbnail( 'medium', array( 'style' => 'width: 100%; height: 100%; object-fit: cover;' ) ); ?>
                        </div>
                    <?php else : ?>
                        <div style="width: 150px; height: 150px; margin: 0 auto var(--spacing-lg); border-radius: 50%; background: linear-gradient(135deg, var(--color-primary), var(--color-primary-dark)); display: flex; align-items: center; justify-content: center; border: 5px solid var(--color-bg-lighter); box-shadow: 0 10px 30px rgba(0,0,0,0.1);">
                            <span style="font-size: 64px;">👥</span>
                        </div>
                    <?php endif; ?>

                    <h1 class="entry-title" style="font-size: var(--font-size-3xl); margin-bottom: var(--spacing-md); background: linear-gradient(135deg, var(--color-primary), var(--color-primary-dark)); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;">
                        <?php the_title(); ?>
                    </h1>

                    <?php if ( has_excerpt() ) : ?>
                        <div class="page-excerpt" style="font-size: var(--font-size-xl); color: var(--color-text-secondary); max-width: 600px; margin: 0 auto; line-height: 1.8;">
                            <?php the_excerpt(); ?>
                        </div>
                    <?php endif; ?>
                </header>

                <!-- المحتوى الرئيسي -->
                <div class="entry-content" style="font-size: var(--font-size-lg); line-height: 2; margin-bottom: var(--spacing-xxl);">
                    <?php the_content(); ?>
                </div>

                <!-- قسم فريق العمل -->
                <?php
                $team_members = get_post_meta( get_the_ID(), 'team_members', true );
                if ( ! empty( $team_members ) && is_array( $team_members ) ) : ?>

                    <section class="team-section" style="padding: var(--spacing-xxl) 0; background: linear-gradient(135deg, var(--color-bg-section) 0%, var(--color-bg-lighter) 100%); margin: 0 calc(-1 * var(--spacing-lg)) 0; border-radius: var(--radius-xl);">
                        <div class="container">
                            <div class="section-header" style="text-align: center; margin-bottom: var(--spacing-xl);">
                                <h2 style="font-size: var(--font-size-3xl); margin-bottom: var(--spacing-sm); background: linear-gradient(135deg, var(--color-primary), var(--color-primary-dark)); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;">
                                    <?php esc_html_e( 'فريق العمل', 'nadiim' ); ?>
                                </h2>
                                <p style="color: var(--color-text-secondary); font-size: var(--font-size-lg);">
                                    <?php esc_html_e( 'تعرف على الأشخاص الذين يعملون بجد لتقديم محتوى مميز', 'nadiim' ); ?>
                                </p>
                            </div>

                            <div class="team-grid" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: var(--spacing-xl);">
                                <?php foreach ( $team_members as $member ) :
                                    $name = isset( $member['name'] ) ? $member['name'] : '';
                                    $role = isset( $member['role'] ) ? $member['role'] : '';
                                    $bio = isset( $member['bio'] ) ? $member['bio'] : '';
                                    $image = isset( $member['image'] ) ? $member['image'] : '';
                                    $email = isset( $member['email'] ) ? $member['email'] : '';
                                    $social = isset( $member['social'] ) ? $member['social'] : array();
                                    $image_url = $image ? wp_get_attachment_image_url( $image, 'medium' ) : '';

                                    if ( empty( $name ) ) continue;
                                ?>
                                    <div class="team-member-card" style="text-align: center; padding: var(--spacing-xl); background: #fff; border-radius: var(--radius-xl); box-shadow: 0 5px 20px rgba(0,0,0,0.08); transition: transform 0.3s ease, box-shadow 0.3s ease; border-top: 4px solid var(--color-primary);">
                                        <!-- صورة العضو -->
                                        <div style="width: 150px; height: 150px; margin: 0 auto var(--spacing-lg); border-radius: 50%; overflow: hidden; border: 5px solid var(--color-bg-section); box-shadow: 0 8px 20px rgba(0,0,0,0.1); position: relative;">
                                            <?php if ( $image_url ) : ?>
                                                <img src="<?php echo esc_url( $image_url ); ?>"
                                                     alt="<?php echo esc_attr( $name ); ?>"
                                                     style="width: 100%; height: 100%; object-fit: cover;" />
                                            <?php else : ?>
                                                <div style="width: 100%; height: 100%; background: linear-gradient(135deg, var(--color-primary), var(--color-primary-dark)); display: flex; align-items: center; justify-content: center;">
                                                    <span style="color: #fff; font-size: var(--font-size-3xl); font-weight: 700;">
                                                        <?php echo esc_html( mb_substr( $name, 0, 1 ) ); ?>
                                                    </span>
                                                </div>
                                            <?php endif; ?>
                                        </div>

                                        <!-- معلومات العضو -->
                                        <h3 style="font-size: var(--font-size-xl); margin-bottom: var(--spacing-xs); color: var(--color-text);">
                                            <?php echo esc_html( $name ); ?>
                                        </h3>

                                        <?php if ( $role ) : ?>
                                            <p style="color: var(--color-primary); font-weight: 600; margin-bottom: var(--spacing-md); font-size: var(--font-size-base);">
                                                <?php echo esc_html( $role ); ?>
                                            </p>
                                        <?php endif; ?>

                                        <?php if ( $bio ) : ?>
                                            <p style="color: var(--color-text-secondary); line-height: 1.7; margin-bottom: var(--spacing-md); font-size: 15px;">
                                                <?php echo esc_html( $bio ); ?>
                                            </p>
                                        <?php endif; ?>

                                        <!-- معلومات التواصل -->
                                        <div style="display: flex; gap: var(--spacing-sm); justify-content: center; align-items: center; margin-top: var(--spacing-lg); padding-top: var(--spacing-md); border-top: 1px solid var(--color-border-light);">
                                            <?php if ( $email ) : ?>
                                                <a href="mailto:<?php echo esc_attr( $email ); ?>"
                                                   style="display: inline-flex; align-items: center; justify-content: center; width: 40px; height: 40px; border-radius: 50%; background: var(--color-bg-section); color: var(--color-primary); transition: all 0.3s ease; text-decoration: none;">
                                                    <?php echo nadiim_get_icon( 'email' ); ?>
                                                </a>
                                            <?php endif; ?>

                                            <?php if ( ! empty( $social ) ) :
                                                foreach ( $social as $platform => $url ) :
                                                    if ( ! empty( $url ) ) : ?>
                                                        <a href="<?php echo esc_url( $url ); ?>"
                                                           target="_blank"
                                                           rel="noopener noreferrer"
                                                           style="display: inline-flex; align-items: center; justify-content: center; width: 40px; height: 40px; border-radius: 50%; background: var(--color-bg-section); color: var(--color-primary); transition: all 0.3s ease; text-decoration: none;">
                                                            <?php echo nadiim_get_icon( $platform ); ?>
                                                        </a>
                                                    <?php endif;
                                                endforeach;
                                            endif; ?>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </section>

                <?php endif; ?>

            </article>

        <?php endwhile; ?>

    </div>
</main>

<?php
get_footer();
