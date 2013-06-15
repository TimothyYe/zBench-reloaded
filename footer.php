    </div><!--wrapper-->
<div class="clear"></div>
<div id="footer">
	<div id="footer-inside">
		<p>
			<?php _e('Copyright', 'zbench'); ?> &copy; <?php echo date("Y"); ?> <?php bloginfo('name'); ?>
			| Powered by <a href="http://wordpress.org/">WordPress</a> and <a href="http://zww.me">zBench</a> <script type="text/javascript" src="http://tajs.qq.com/stats?sId=19418239" charset="UTF-8"></script> <?php echo get_num_queries(); ?> queries in <?php timer_stop(3); ?> seconds
		</p>
		<span id="back-to-top">&uarr; <a href="#" rel="nofollow" title="Back to top"><?php _e('Top', 'zbench'); ?></a></span>
	</div>
</div><!--footer-->
<?php wp_footer(); ?>
</body>

<script type="text/javascript">
$("#loading_bar").animate({width:"100%"},function(){
setTimeout(function(){$("#loading").hide();},1000);
}); 
</script>

</html>
