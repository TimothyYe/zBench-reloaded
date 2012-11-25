    </div><!--wrapper-->
<div class="clear"></div>
<div id="footer">
	<div id="footer-inside">
		<p>
			<?php _e('Copyright', 'zbench'); ?> &copy; <?php echo date("Y"); ?> <?php bloginfo('name'); ?>
			| Powered by <a href="http://wordpress.org/">WordPress</a> and <a href="http://zww.me">zBench</a>   <script language="javascript" src="http://count24.51yes.com/click.aspx?id=242284288&logo=1" charset="gb2312"></script> <?php echo get_num_queries(); ?> queries in <?php timer_stop(3); ?> seconds
		</p>
		<span id="back-to-top">&uarr; <a href="#" rel="nofollow" title="Back to top"><?php _e('Top', 'zbench'); ?></a></span>
	</div>
</div><!--footer-->
<?php wp_footer(); ?>
</body>
</html>
