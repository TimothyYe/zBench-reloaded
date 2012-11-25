<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" <?php language_attributes(); ?>>
<head profile="http://gmpg.org/xfn/11">
	<meta http-equiv="Content-Type" content="<?php bloginfo('html_type'); ?>; charset=<?php bloginfo('charset'); ?>" />
	<title>
	<?php
	global $page, $paged;
	wp_title( '|', true, 'right' );
	bloginfo( 'name' );
	$site_description = get_bloginfo( 'description', 'display' );
	if ( $site_description && ( is_home() || is_front_page() ) )
		echo " | $site_description";
	if ( $paged >= 2 || $page >= 2 )
		echo ' | ' . sprintf( __( 'Page %s', 'zbench' ), max( $paged, $page ) );
	?>
	</title>
<script type="text/javascript" src="<?php bloginfo('template_directory'); ?>/js/jquery.min.js"></script>
<script type="text/javascript" src="<?php bloginfo('template_directory'); ?>/js/timothy.js"></script>
<script type="text/javascript" src="<?php bloginfo('template_directory'); ?>/js/jquery.lazyload.mini.js"></script>
<script type="text/javascript" charset="utf-8">
      $(function() {
          $("img").lazyload({
             placeholder : "<?php bloginfo('template_directory'); ?>/images/grey.gif",
             effect: "fadeIn"
          });
      });
</script>

	<?php
        //判断是否为首页
        if (is_home()) {
           $description ="Timothy Space | Timothy的技术博客,原VC源动力站点,关注程序开发,IT技术,VPS技术,Wordpress技术,计算机与互联网,以及生活点滴记录";
           $keywords ="Timothy,Space,Blog,博客,计算机,互联网,IT技术,VC源动力,DotNet开发,VC开发,DotNET教程,VPS教程,Wordpress";
           //判断是否为文章页
         } else if (is_single()) {
         if ($post->post_excerpt) {
          $description = $post->post_excerpt;
         } else {
         $description = mb_strimwidth(strip_tags(
         apply_filters('the_content',$post->post_content)
         ),0,220);
         }
         $keywords = "";
         $tags = wp_get_post_tags($post->ID);
         foreach ($tags as $tag ) {
            $keywords = $keywords . $tag->name . ",";
         }
        //判断是否为分类页
        } else if (is_category()) {
        $description = category_description();
        }
        ?>
	<meta content="<?php echo $keywords; ?>" name="keywords" />
	<meta content="<?php echo $description; ?>"name="description" />


	<?php if ( is_singular() && get_option('thread_comments') ) wp_enqueue_script('comment-reply'); ?>
	<link rel="stylesheet" type="text/css" media="all" href="<?php bloginfo('stylesheet_url'); ?>" />
	<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />
	<link href="<?php bloginfo('template_directory'); ?>/images/favicon.ico" rel="shortcut icon">
	<?php wp_head(); ?>

	<?php if ( is_singular() ){ /* 只在单个页面加载 */ ?>
	    <link rel="stylesheet" type="text/css" href="<?php bloginfo('template_directory'); ?>/thickbox/thickbox.css">
	    <script type="text/javascript" src="<?php bloginfo('template_directory'); ?>/thickbox/thickbox-compressed.js"></script>
	<?php } ?>
</head>
<body <?php body_class(); ?>>
<div id="nav">
	<div id="menus">
		<ul><li<?php if (is_home() || is_front_page()) echo ' class="current_page_item"'; ?>><a href="<?php echo home_url('/'); ?>"><?php _e('Home', 'zbench'); ?></a></li></ul>
		<?php wp_nav_menu( array( 'theme_location' => 'primary', 'fallback_cb' => 'zbench_wp_list_pages', 'container' => 'false', 'items_wrap' => '<ul id="%1$s" class="%2$s">%3$s</ul>' ) ); ?>
	</div>
	<div id="search">
		<?php get_search_form(); ?>
	</div>
</div>
<?php global $zbench_options; ?>
<div id="wrapper"<?php if($zbench_options['left_sidebar']==TRUE) echo ' class="LorR"'; ?>>
	<div id="header"><?php $logo=''; if($zbench_options['logo_url']!='') $logo=' class="header_logo" style="background:url('.$zbench_options['logo_url'].') no-repeat 0 0"'; ?>
		<h1<?php if($zbench_options['hide_title']!='') echo ' class="hidden"'; ?>><a href="<?php echo home_url('/'); ?>"<?php if($logo) echo $logo; ?>><?php bloginfo('name'); ?></a></h1>
		<h2<?php if($logo || $zbench_options['hide_title']!='') echo ' class="hidden"'; ?>><?php bloginfo('description');?></h2>
		<div class="clear"></div>
		<?php if ( get_header_image() != '' ) : ?>
		<div id="header_image">
			<div id="header_image_border">
				<a href="<?php if($zbench_options['header_image_url']!='') { echo $zbench_options['header_image_url']; } else { echo home_url('/'); } ?>"><img src="<?php header_image(); ?>" width="<?php echo HEADER_IMAGE_WIDTH; ?>" height="<?php echo HEADER_IMAGE_HEIGHT; ?>" alt="" /></a>
			</div>
		</div>
		<?php endif; ?>
	</div>
