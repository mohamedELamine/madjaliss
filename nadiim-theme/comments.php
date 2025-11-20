<?php
/**
 * قالب التعليقات
 *
 * قالب لعرض التعليقات والردود
 *
 * @package Nadiim
 * @since 2.0.0
 */

if ( post_password_required() ) {
	return;
}
?>

<div id="comments" class="comments-area">

	<?php if ( have_comments() ) : ?>
		<h2 class="comments-title">
			<?php
			$comments_number = get_comments_number();
			if ( $comments_number === 1 ) {
				echo 'تعليق واحد';
			} else {
				printf(
					/* translators: %s: عدد التعليقات */
					'%s تعليقات',
					number_format_i18n( $comments_number )
				);
			}
			?>
		</h2>

		<ol class="comment-list">
			<?php
			wp_list_comments(
				array(
					'style'       => 'ol',
					'short_ping'  => true,
					'avatar_size' => 64,
				)
			);
			?>
		</ol>

		<?php
		the_comments_navigation(
			array(
				'prev_text' => '← تعليقات أقدم',
				'next_text' => 'تعليقات أحدث →',
			)
		);
		?>

	<?php endif; ?>

	<?php if ( ! comments_open() && get_comments_number() && post_type_supports( get_post_type(), 'comments' ) ) : ?>
		<p class="no-comments">التعليقات مغلقة.</p>
	<?php endif; ?>

	<?php
	comment_form(
		array(
			'title_reply'         => 'اترك تعليقاً',
			'title_reply_to'      => 'الرد على %s',
			'cancel_reply_link'   => 'إلغاء الرد',
			'label_submit'        => 'إرسال التعليق',
			'comment_field'       => '<p class="comment-form-comment"><label for="comment">التعليق *</label><textarea id="comment" name="comment" cols="45" rows="8" maxlength="65525" required="required"></textarea></p>',
			'must_log_in'         => '<p class="must-log-in">' . sprintf( 'يجب أن تكون <a href="%s">مسجلاً للدخول</a> لإضافة تعليق.', wp_login_url( apply_filters( 'the_permalink', get_permalink() ) ) ) . '</p>',
			'logged_in_as'        => '<p class="logged-in-as">' . sprintf( 'مسجل الدخول كـ <a href="%1$s">%2$s</a>. <a href="%3$s" title="تسجيل الخروج من هذا الحساب">تسجيل الخروج؟</a>', admin_url( 'profile.php' ), wp_get_current_user()->display_name, wp_logout_url( apply_filters( 'the_permalink', get_permalink() ) ) ) . '</p>',
			'comment_notes_before' => '<p class="comment-notes">لن يتم نشر عنوان بريدك الإلكتروني. الحقول الإلزامية مشار إليها بـ *</p>',
			'comment_notes_after' => '',
			'fields'              => array(
				'author' => '<p class="comment-form-author"><label for="author">الاسم *</label> <input id="author" name="author" type="text" value="' . esc_attr( $commenter['comment_author'] ) . '" size="30" maxlength="245" required="required" /></p>',
				'email'  => '<p class="comment-form-email"><label for="email">البريد الإلكتروني *</label> <input id="email" name="email" type="email" value="' . esc_attr( $commenter['comment_author_email'] ) . '" size="30" maxlength="100" aria-describedby="email-notes" required="required" /></p>',
				'url'    => '<p class="comment-form-url"><label for="url">الموقع الإلكتروني</label> <input id="url" name="url" type="url" value="' . esc_attr( $commenter['comment_author_url'] ) . '" size="30" maxlength="200" /></p>',
			),
		)
	);
	?>

</div><!-- #comments -->
