<?php
/**
 * Comments Template
 */

// Prevent direct file access
if (!defined('ABSPATH')) {
    exit;
}

if (post_password_required()) {
    return;
}
?>

<div id="comments" class="comments-area mb-4">
    
    <?php if (have_comments()) : ?>
        <h3 class="comments-title mb-4">
            <?php
            $comment_count = get_comments_number();
            if ('1' === $comment_count) {
                printf(
                    esc_html__('One thought on &ldquo;%1$s&rdquo;', 'blog-bootstrap'),
                    '<span>' . get_the_title() . '</span>'
                );
            } else {
                printf(
                    esc_html(_nx(
                        '%1$s thought on &ldquo;%2$s&rdquo;',
                        '%1$s thoughts on &ldquo;%2$s&rdquo;',
                        $comment_count,
                        'comments title',
                        'blog-bootstrap'
                    )),
                    number_format_i18n($comment_count),
                    '<span>' . get_the_title() . '</span>'
                );
            }
            ?>
        </h3>

        <?php the_comments_navigation(); ?>

        <ol class="comment-list">
            <?php
            wp_list_comments(array(
                'style'      => 'ol',
                'short_ping' => true,
                'avatar_size' => 60,
                'callback'   => 'blog_bootstrap_comment_callback',
            ));
            ?>
        </ol>

        <?php the_comments_navigation(); ?>

        <?php if (!comments_open()) : ?>
            <p class="no-comments"><?php esc_html_e('Comments are closed.', 'blog-bootstrap'); ?></p>
        <?php endif; ?>

    <?php endif; ?>

    <?php
    comment_form(array(
        'title_reply'         => esc_html__('Leave a Reply', 'blog-bootstrap'),
        'title_reply_to'      => esc_html__('Leave a Reply to %s', 'blog-bootstrap'),
        'title_reply_before'  => '<h3 id="reply-title" class="comment-reply-title">',
        'title_reply_after'   => '</h3>',
        'cancel_reply_before' => ' <small>',
        'cancel_reply_after'  => '</small>',
        'cancel_reply_link'   => esc_html__('Cancel reply', 'blog-bootstrap'),
        'label_submit'        => esc_html__('Post Comment', 'blog-bootstrap'),
        'submit_button'       => '<input name="%1$s" type="submit" id="%2$s" class="%3$s btn btn-primary" value="%4$s" />',
        'submit_field'        => '<div class="form-submit mt-3">%1$s %2$s</div>',
        'comment_field'        => '<div class="form-group mb-3"><label for="comment" class="form-label">' . esc_html_x('Comment', 'noun', 'blog-bootstrap') . '</label><textarea id="comment" name="comment" class="form-control" rows="6" required></textarea></div>',
        'fields'              => array(
            'author' => '<div class="form-group mb-3"><label for="author" class="form-label">' . esc_html__('Name', 'blog-bootstrap') . ' <span class="required">*</span></label><input id="author" name="author" type="text" class="form-control" value="' . esc_attr($commenter['comment_author']) . '" required></div>',
            'email'  => '<div class="form-group mb-3"><label for="email" class="form-label">' . esc_html__('Email', 'blog-bootstrap') . ' <span class="required">*</span></label><input id="email" name="email" type="email" class="form-control" value="' . esc_attr($commenter['comment_author_email']) . '" required></div>',
            'url'    => '<div class="form-group mb-3"><label for="url" class="form-label">' . esc_html__('Website', 'blog-bootstrap') . '</label><input id="url" name="url" type="url" class="form-control" value="' . esc_attr($commenter['comment_author_url']) . '"></div>',
        ),
        'class_form'          => 'comment-form',
        'class_submit'         => 'btn btn-primary',
    ));
    ?>

</div>

<?php
/**
 * Custom comment callback
 */
function blog_bootstrap_comment_callback($comment, $args, $depth) {
    if ('div' === $args['style']) {
        $tag       = 'div';
        $add_below = 'comment';
    } else {
        $tag       = 'li';
        $add_below = 'div-comment';
    }
    ?>
    <<?php echo $tag; ?> <?php comment_class(empty($args['has_children']) ? '' : 'parent'); ?> id="comment-<?php comment_ID(); ?>">
    <?php if ('div' != $args['style']) : ?>
        <div id="div-comment-<?php comment_ID(); ?>" class="comment-body">
    <?php endif; ?>
    
    <div class="comment-author vcard">
        <?php if (0 != $args['avatar_size']) {
            echo get_avatar($comment, $args['avatar_size'], '', '', array('class' => 'rounded-circle me-3'));
        } ?>
        <?php printf(__('%s <span class="says">says:</span>'), sprintf('<cite class="fn">%s</cite>', get_comment_author_link())); ?>
    </div>
    
    <?php if ('0' == $comment->comment_approved) : ?>
        <em class="comment-awaiting-moderation"><?php esc_html_e('Your comment is awaiting moderation.', 'blog-bootstrap'); ?></em>
        <br />
    <?php endif; ?>

    <div class="comment-meta commentmetadata">
        <a href="<?php echo htmlspecialchars(get_comment_link($comment->comment_ID)); ?>">
            <time datetime="<?php comment_time('c'); ?>">
                <?php
                /* translators: 1: date, 2: time */
                printf(esc_html__('%1$s at %2$s', 'blog-bootstrap'), get_comment_date(), get_comment_time());
                ?>
            </time>
        </a>
        <?php edit_comment_link(__('(Edit)', 'blog-bootstrap'), '  ', ''); ?>
    </div>

    <?php comment_text(); ?>

    <div class="reply">
        <?php
        comment_reply_link(
            array_merge(
                $args,
                array(
                    'add_below' => $add_below,
                    'depth'     => $depth,
                    'max_depth' => $args['max_depth'],
                    'before'    => '<div class="reply">',
                    'after'     => '</div>',
                    'class'     => 'btn btn-outline-secondary btn-sm'
                )
            )
        );
        ?>
    </div>
    
    <?php if ('div' != $args['style']) : ?>
        </div>
    <?php endif; ?>
    <?php
}