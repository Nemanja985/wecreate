<?php get_header(); ?>

<div class="container-wrap">
	
	<div class="container main-content">
		
		<div class="row">
			
			<div class="col span_12">
				
				<div id="error-404">
					<img src="/wp-content/themes/tgi-child/dist/images/tgi_404_icon-empty-plate.svg">
					<h2><?php echo __('It’s an empty plate!', NECTAR_THEME_NAME); ?></h2>
					<p>There seems to be a broken link or a missing page. Please try again.</p>
					<ul>
						<li><a href="<?php echo get_site_url(); ?>">Go to Home</a></li>
						<li>Support</li>
					</ul>
				</div>
				
			</div><!--/span_12-->
			
		</div><!--/row-->
		
	</div><!--/container-->

</div>
<?php get_footer(); ?>

