<?php /* Template Name: Blog Page */ ?>
<?php get_header(); ?>
<?php global $post; $page_slug = $post->post_name;  ?>
<div id="blog">
	<div class="container">

		<div class="banner-blog">
			<div class="table-box" style="background-image: url('<?php the_post_thumbnail_url();?>');">
				<div class="table-cell">
					<h2><?php echo get_the_title(); ?></h2>
				</div>
			</div>
		</div>

		<div class="boxes-blog">
			<div class="row">
				<?php
					$args = array(
					    'post_type' => 'post',
					    'post_status' => 'publish',
					    'posts_per_page' => 6,
					    'category_name' => $page_slug,
					);
					$arr_posts = new WP_Query( $args );
					 
					if ( $arr_posts->have_posts() ) :
					 
					    while ( $arr_posts->have_posts() ) :
					        $arr_posts->the_post();
					        if (get_field('link_blog')) { ?>
					        	<div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
					        	<a target="_blank" href="<?php echo get_field('link_blog'); ?>">
						        	<div class="box-blog">
						        		<div class="box-img"><img src="<?php the_post_thumbnail_url(); ?>"></div>
						        		<p class="date-post"><?php echo get_the_date('M Y'); ?></p>
						        		<h3><?php echo get_the_title(); ?></h3>
						        		<p><?php echo wp_trim_words( get_the_excerpt(), 20, '...' ); ?></p>
						        	</div>
					        	</a>
					        	</div>
					        <?php }else{ ?>
					        	<div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
					        	<a href="<?php echo get_the_permalink(); ?>">
						        	<div class="box-blog">
						        		<div class="box-img"><img src="<?php the_post_thumbnail_url(); ?>"></div>
						        		<p class="date-post"><?php echo get_the_date('M Y'); ?></p>
						        		<h3><?php echo get_the_title(); ?></h3>
						        		<p><?php echo wp_trim_words( get_the_excerpt(), 20, '...' ); ?></p>
						        	</div>
					        	</a>
					        	</div>
					        <?php } ?>
					        
					        <?php
					    endwhile;
					    wp_reset_postdata();
					endif;
				?>

			  </div>
		</div>

	</div>
</div>

<?php get_footer(); ?>