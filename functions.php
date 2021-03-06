<?php


/* Mini Pagenavi v1.0 by Willin Kan. */
if ( !function_exists('pagenavi') ) {
	function pagenavi( $p = 4 ) { // 取当前页前后各 2 页，根据需要改
		if ( is_singular() ) return; // 文章与插页不用
		global $wp_query, $paged;
		$max_page = $wp_query->max_num_pages;
		if ( $max_page == 1 ) return; // 只有一页不用
		if ( empty( $paged ) ) $paged = 1;
		echo '<span class="pages">Page: ' . $paged . ' of ' . $max_page . ' </span> '; // 显示页数
		if ( $paged > $p + 1 ) p_link( 1, '最前页' );
		if ( $paged > $p + 2 ) echo '... ';
		for( $i = $paged - $p; $i <= $paged + $p; $i++ ) { // 中间页
			if ( $i > 0 && $i <= $max_page ) $i == $paged ? print "<span class='page-navi-numbers page-navi-current-page'>{$i}</span> " : p_link( $i );
		}
		if ( $paged < $max_page - $p - 1 ) echo '... ';
		if ( $paged < $max_page - $p ) p_link( $max_page, '最后页' );
	}
	function p_link( $i, $title = '' ) {
		if ( $title == '' ) $title = "第 {$i} 页";
		echo "<a class='page-navi-numbers' href='", esc_html( get_pagenum_link( $i ) ), "' title='{$title}'>{$i}</a> ";
	}
}


if( !function_exists('related_posts')) {
function related_posts() {
$post_num = 10; // 數量設定.
$exclude_id = $post->ID; // 單獨使用要開此行 //zww: edit
$posttags = get_the_tags(); $i = 0;
if ( $posttags ) {
	$tags = ''; foreach ( $posttags as $tag ) $tags .= $tag->term_id . ','; //zww: edit
	$args = array(
		'post_status' => 'publish',
		'tag__in' => explode(',', $tags), // 只選 tags 的文章. //zww: edit
		'post__not_in' => explode(',', $exclude_id), // 排除已出現過的文章.
		'caller_get_posts' => 1,
		'orderby' => 'comment_date', // 依評論日期排序.
		'posts_per_page' => $post_num
	);
	query_posts($args);
	while( have_posts() ) { the_post(); ?>
		<li><a rel="bookmark" href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><?php the_title(); ?></a></li>
	<?php
		$exclude_id .= ',' . $post->ID; $i ++;
	} wp_reset_query();
}

/*
if ( $i < $post_num ) { // 當 tags 文章數量不足, 再取 category 補足.
	$cats = ''; foreach ( get_the_category() as $cat ) $cats .= $cat->cat_ID . ',';
	$args = array(
		'category__in' => explode(',', $cats), // 只選 category 的文章.
		'post__not_in' => explode(',', $exclude_id),
		'caller_get_posts' => 1,
		'orderby' => 'comment_date',
		'posts_per_page' => $post_num - $i
	);
	query_posts($args);
	while( have_posts() ) { the_post(); ?>
		<li><a rel="bookmark" href="<?php the_permalink(); ?>"><?php the_title(); ?></a></li>
	<?php $i++;
	} wp_reset_query();
} */

if ( $i  == 0 )  echo '<li>没有相关文章!</li>';
}
}


if( !function_exists('recent_comments'))
{
	function recent_comments()
	{
global $wpdb;
$limit_num = '5'; //这里定义显示的评论数量
$my_email = "'" . get_bloginfo ('admin_email') . "'"; //这里是自动检测博主的邮件，实现博主的评论不显示
$rc_comms = $wpdb->get_results("
 SELECT ID, post_title, comment_ID, comment_author, comment_author_email, comment_content
 FROM $wpdb->comments LEFT OUTER JOIN $wpdb->posts
 ON ($wpdb->comments.comment_post_ID = $wpdb->posts.ID)
 WHERE comment_approved = '1'
 AND comment_type = ''
 AND post_password = ''
 AND comment_author_email != $my_email
 ORDER BY comment_date_gmt
 DESC LIMIT $limit_num
 ");
$rc_comments = '';
foreach ($rc_comms as $rc_comm) { //get_avatar($rc_comm,$size='50')
$rc_comments .= "<li>". get_avatar($rc_comm,$size='50') ."<span class='zsnos_comment_author'>" . $rc_comm->comment_author . ": </span><a href='"
. get_permalink($rc_comm->ID) . "#comment-" . $rc_comm->comment_ID
//. htmlspecialchars(get_comment_link( $rc_comm->comment_ID, array('type' => 'comment'))) // 可取代上一行, 会显示评论分页ID, 但较耗资源
. "' title='on " . $rc_comm->post_title . "'>" . strip_tags($rc_comm->comment_content)
. "</a></li>\n";
}
$rc_comments = convert_smilies($rc_comments);
echo $rc_comments;
	}
}

//Auto add copyright info at the end of articles
function insertFootNote($content) {
	if(is_single() || is_feed()) { //如果不想feed输出就去掉“|| is_feed()”
		$wzbt = get_the_title();
		$wzlj = get_permalink($post->ID);
		$content.= '<p class="announce">';
		$content.= '<span style="font-weight:bold;text-shadow:0 1px 0 #ddd;">声明:</span> 此Blog中的文章和随笔仅代表作者在某一特定时间内的观点和结论，对其完全的正确不做任何担保或假设 <br /> 本站文章均采用 <a rel="nofollow" href="http://creativecommons.org/licenses/by-nc-sa/3.0/" title="署名-非商业性使用-相同方式共享">知识共享署名-相同方式共享3.0</a> 协议进行授权，';
		$content.= '除非注明，本站文章均为原创，转载请注明转自  <a href="'.home_url().'">'.get_bloginfo('name').'</a> 并应以链接形式标明本文地址!';
		$content.= '</p>';
	}
	return $content;
}
add_filter ('the_content', 'insertFootNote');

//////// Widgetized Sidebar.
function zbench_widgets_init() {
	register_sidebar(array(
		'name' => __('Primary Widget Area','zbench'),
		'id' => 'primary-widget-area',
		'description' => __('The primary widget area','zbench'),
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
		'after_widget' => '</div>',
		'before_title' => '<h3 class="widgettitle">',
		'after_title' => '</h3>'
	));
	register_sidebar(array(
		'name' => __('Singular Widget Area','zbench'),
		'id' => 'singular-widget-area',
		'description' => __('The singular widget area','zbench'),
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
		'after_widget' => '</div>',
		'before_title' => '<h3 class="widgettitle">',
		'after_title' => '</h3>'
	));
	register_sidebar(array(
		'name' => __('Not Singular Widget Area','zbench'),
		'id' => 'not-singular-widget-area',
		'description' => __('Not the singular widget area','zbench'),
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
		'after_widget' => '</div>',
		'before_title' => '<h3 class="widgettitle">',
		'after_title' => '</h3>'
	));
	register_sidebar(array(
		'name' => __('Footer Widget Area','zbench'),
		'id' => 'footer-widget-area',
		'description' => __('The footer widget area','zbench'),
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
		'after_widget' => '</div>',
		'before_title' => '<h3 class="widgettitle">',
		'after_title' => '</h3>'
	));
}
add_action( 'widgets_init', 'zbench_widgets_init' );

//////// Custom Comments List.
function zbench_mytheme_comment($comment, $args, $depth) {
   $GLOBALS['comment'] = $comment;
	switch ($pingtype=$comment->comment_type) {
		case 'pingback' :
		case 'trackback' : ?>

<li <?php comment_class(); ?> id="li-comment-<?php comment_ID(); ?>">
	<div id="comment-<?php comment_ID(); ?>">
		<div class="comment-author vcard pingback">
			<cite class="fn zbench_pingback"><?php comment_author_link(); ?> - <?php echo $pingtype; ?> on <?php printf(__('%1$s at %2$s', 'zbench'), get_comment_date(),  get_comment_time()); ?></cite>
		</div>
	</div>
<?php
			break;
		default : ?>

<li <?php comment_class(); ?> id="li-comment-<?php comment_ID(); ?>">
	<div id="comment-<?php comment_ID(); ?>">
		<div class="comment-author vcard">
			<?php echo get_avatar($comment,$size='40',$default='' ); ?>
			<cite class="fn"><?php comment_author_link(); ?></cite>
			<span class="comment-meta commentmetadata"><a href="<?php echo htmlspecialchars( get_comment_link( $comment->comment_ID ) ); ?>"><?php printf(__('%1$s at %2$s', 'zbench'), get_comment_date(),  get_comment_time()); ?></a><?php edit_comment_link(__('[Edit]','zbench'),' ',''); ?></span>
		</div>
		<div class="comment-text">
			<?php comment_text(); ?>
			<?php if ($comment->comment_approved == '0') : ?>
			<p><em class="approved"><?php _e('Your comment is awaiting moderation.','zbench'); ?></em></p>
			<?php endif; ?>
		</div>
		<div class="reply">
			<?php comment_reply_link(array_merge( $args, array('depth' => $depth, 'max_depth' => $args['max_depth']))); ?>
		</div>
	</div>

<?php 		break;
	}
}

//////// wp_list_comments()->pings callback
function zbench_custom_pings($comment, $args, $depth) {
    $GLOBALS['comment'] = $comment;
    if('pingback' == get_comment_type()) $pingtype = 'Pingback';
    else $pingtype = 'Trackback';
?>
    <li id="comment-<?php echo $comment->comment_ID ?>">
        <?php comment_author_link(); ?> - <?php echo $pingtype; ?> on <?php echo mysql2date('Y/m/d/ H:i', $comment->comment_date); ?>
<?php }

//////// Set the content width based on the theme's design and stylesheet.
if ( ! isset( $content_width ) )
	$content_width = 620;
	
//////// WP nav menu
register_nav_menus(array('primary' => 'Primary Navigation'));
//////// Custom wp_list_pages
function zbench_wp_list_pages(){
	echo '<ul>' , wp_list_pages('title_li=') , '</ul>';
}

//////// LOCALIZATION
load_theme_textdomain('zbench', get_template_directory() . '/lang');

//////// custom excerpt
function zbench_excerpt_length( $length ) {
	return 40;
}
add_filter( 'excerpt_length', 'zbench_excerpt_length' );
//Returns a "Read more &raquo;" link for excerpts
function zbench_continue_reading_link() {
	return '<p class="read-more"><a href="'. esc_url(get_permalink()) . '">' . __( 'Read more &raquo;', 'zbench' ) . '</a></p>';
}
//Replaces "[...]" (appended to automatically generated excerpts) with an ellipsis and zbench_continue_reading_link().
function zbench_auto_excerpt_more( $more ) {
	return ' &hellip;' . zbench_continue_reading_link();
}
add_filter( 'excerpt_more', 'zbench_auto_excerpt_more' );
//Adds a pretty "Read more &raquo;" link to custom post excerpts.
function zbench_custom_excerpt_more( $output ) {
	if ( has_excerpt() && ! is_attachment() ) {
		$output .= zbench_continue_reading_link();
	}
	return $output;
}
add_filter( 'get_the_excerpt', 'zbench_custom_excerpt_more' );
//Custom more-links for zBench
function zbench_custom_more_link($link) { 
	return '<span class="zbench-more-link">'.$link.'</span>';
}
add_filter('the_content_more_link', 'zbench_custom_more_link');

//////// Tell WordPress to run zbench_setup() when the 'after_setup_theme' hook is run.
add_action( 'after_setup_theme', 'zbench_setup' );
if ( ! function_exists( 'zbench_setup' ) ):
function zbench_setup() {

	// Add default posts and comments RSS feed links to head
	add_theme_support( 'automatic-feed-links' );

	// This theme styles the visual editor with editor-style.css to match the theme style.
	add_editor_style();

	// This theme allows users to set a custom background
	add_custom_background();
	
	// This theme uses post thumbnails
	add_theme_support( 'post-thumbnails' );
	add_image_size( 'extra-featured-image', 620, 200, true );
	function zbench_featured_content($content) {
		if (is_home() || is_archive()) {
			the_post_thumbnail( 'extra-featured-image' );
		}
		return $content;
	}
	add_filter( 'the_content', 'zbench_featured_content',1 );
	function zbench_post_image_html( $html, $post_id, $post_image_id ) {
		$html = '<a href="' . get_permalink( $post_id ) . '" title="' . esc_attr( get_post_field( 'post_title', $post_id ) ) . '">' . $html . '</a>';
		return $html;
	}
	add_filter( 'post_thumbnail_html', 'zbench_post_image_html', 10, 3 );

	// Your changeable header business starts here
	define( 'HEADER_TEXTCOLOR', '' );
	// No CSS, just IMG call. The %s is a placeholder for the theme template directory URI.
	define( 'HEADER_IMAGE', '' ); // default: none IMG

	// The height and width of your custom header. You can hook into the theme's own filters to change these values.
	// Add a filter to zbench_header_image_width and zbench_header_image_height to change these values.
	define( 'HEADER_IMAGE_WIDTH', apply_filters( 'zbench_header_image_width', 950 ) );
	define( 'HEADER_IMAGE_HEIGHT', apply_filters( 'zbench_header_image_height', 180 ) );

	// We'll be using post thumbnails for custom header images on posts and pages.
	// We want them to be 950 pixels wide by 180 pixels tall.
	// Larger images will be auto-cropped to fit, smaller ones will be ignored. See header.php.
	set_post_thumbnail_size( HEADER_IMAGE_WIDTH, HEADER_IMAGE_HEIGHT, true );

	// Don't support text inside the header image.
	define( 'NO_HEADER_TEXT', true );

	// Add a way for the custom header to be styled in the admin panel that controls
	// custom headers. See zbench_admin_header_style(), below.
	add_custom_image_header( '', 'zbench_admin_header_style' );
	if ( ! function_exists( 'zbench_admin_header_style' ) ) {
	//Styles the header image displayed on the Appearance > Header admin panel.
		function zbench_admin_header_style() {
		?>
			<style type="text/css">
			/* Shows the same border as on front end */
			#headimg { }
			/* If NO_HEADER_TEXT is false, you would style the text with these selectors:
				#headimg #name { }
				#headimg #desc { }
			*/
			</style>
		<?php
		}
	}

} // end of zbench_setup()
endif;

//////// Load up our theme options page and related code.
require( dirname( __FILE__ ) . '/library/theme-options.php' );
//////// Load custom theme options
$zbench_options = get_option('zBench_options');
