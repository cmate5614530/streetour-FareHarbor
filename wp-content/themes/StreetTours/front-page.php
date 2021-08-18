<?php /* Template Name: Front Page */ ?>
<?php get_header(); ?>

<div class="main-banner">
  <div class="table-box" <?php if(has_post_thumbnail()){ ?> style="background-image: url('<?php the_post_thumbnail_url();?>');" <?php } ?> >
    <div class="table-cell">
      <div class="container-foundry">
        <h2><?php echo get_the_title(); ?></h2>

        <form method="get" id="searchform" action="<?php echo get_the_permalink(368); ?>">
        	<input type="text" name="search-text-desktop" placeholder="City or activity">
        					<script>
				    			$.ajax({
								  url: "https://geolocation-db.com/jsonp",
								  jsonpCallback: "callback",
								  dataType: "jsonp",
								  success: function(location) {
								    //$('#country').html(location.country_name);
								    //$('#state').html(location.state);
								    $('#city').html(location.city);
								    //$('#latitude').html(location.latitude);
								    //$('#longitude').html(location.longitude);
								    //$('#ip').html(location.IPv4);
								    var lc = location.city;
								    $('#input-locd').append('<input type="hidden" name="my_location" value="'+lc+'">');
								  }
								});
				    		</script>

        	<input type="submit" id="searchsubmit" value="Search">
        	<div id="input-locd"></div>
        </form>

        <p class="slogan"><img src="<?php echo get_template_directory_uri(); ?>/images/leaf.svg">We Support Sustainable Tourism</p>
      </div>

      <div class="container-foundry" style="max-width: 1200px!important;">
        <div class="items">
	        <div class="row">
			    <div class="col-4"><p><img src="<?php echo get_template_directory_uri(); ?>/images/Flag-outline.svg">Best Price Guarantee</p></div>
			    <div class="col-4"><p><img src="<?php echo get_template_directory_uri(); ?>/images/heart-outline.svg">Verified Reviews</p></div>
			    <div class="col-4"><p><img src="<?php echo get_template_directory_uri(); ?>/images/check-icon.svg">Free Cancellation</p></div>
			</div>
		</div>
	  </div>

    </div>
  </div>
</div>

<div class="items responsive-items">
	<div class="container-foundry">
	<div class="row">
		<div class="col-4"><p><img src="<?php echo get_template_directory_uri(); ?>/images/item-1.svg">Best Price<br>Guarantee</p></div>
		<div class="col-4"><p><img src="<?php echo get_template_directory_uri(); ?>/images/item-2.svg">Verified<br>Reviews</p></div>
		<div class="col-4"><p><img src="<?php echo get_template_directory_uri(); ?>/images/item-3.svg">Free<br>Cancellation</p></div>
	</div>
	</div>
</div>

<div class="top-xp">
	<div class="container">
		<?php
			$name_top = get_term(get_field('top_experience'));
		?>
		<h3>Top Experiences in <?php echo $name_top->name; ?></h3>
		<div class="boxes-tour">
			<?php echo do_shortcode('[ajax_load_more post_type="product" posts_per_page="6" taxonomy="locations" taxonomy_terms="'.$name_top->name.'" taxonomy_operator="IN" button_label="View More"]'); ?>
        </div>
	</div>
</div>


<div class="popular">
	<div class="container">
		<h3>Popular Destinations</h3>

		<div class="boxes-popular">
			<ul class="term-list">
			<?php
				if( have_rows('popular_destination') ):
				    while( have_rows('popular_destination') ) : the_row();  $name_popular = get_term(get_sub_field('popular_destination_location'));
				    	$back_pop = get_field('hero_image', 'locations_'.$name_popular->term_id); ?>

				    		<a href="<?php echo get_term_link($name_popular->term_id, 'locations'); ?>">
				    			<div class="box-popular">
									<div class="table-box" style="background-image: url(<?php echo $back_pop; ?>)!important;">
										<div class="table-cell">
											<h4><?php echo $name_popular->name; ?></h4>
										</div>
									</div>
								</div>
				    		</a>

			<?php	endwhile;
				endif;
			?>
			</ul>
		</div>

		<center><a style="margin-top: 10px;" class="white-btn" href="<?php echo get_the_permalink(155); ?>">View All Destinations</a></center>
	</div>
</div>

<div class="explore-london">
    <div class="container">
    	<?php
			$name_explore = get_term(get_field('explore_location'));
		?>
        <h3>Explore <?php echo $name_explore->name; ?></h3>
        <div class="boxes-explore">
            <div class="row">
                        <?php
                                $args = array(
                                'post_type' => 'product',
                                'posts_per_page' => 4,
                                'tax_query' => array(
                                    array (
                                        'taxonomy' => 'locations',
                                        'field' => 'slug',
                                        'terms' => $name_explore->name,
                                    )
                                ),
                            );
                            $arr_posts = new WP_Query( $args );

                            if ( $arr_posts->have_posts() ) :

                                while ( $arr_posts->have_posts() ) :
                                    $arr_posts->the_post();
                                    ?>
                                        <?php echo get_template_part("components/explore","box"); ?>
                                    <?php
                                endwhile;

                            endif;
                            wp_reset_postdata();
                            ?>
            </div>

        </div>
        <center><a class="white-btn" href="">View More</a></center>
    </div>
</div>

<?php echo get_template_part("components/offers","box"); ?>
<?php echo get_template_part("components/offers","boxresponsive"); ?>

<div class="top-destinations">
	<div class="container">
		<h3>Top Destinations</h3>
			<div class="slide-destinations">
				<?php
				if( have_rows('top_destination') ):
				    while( have_rows('top_destination') ) : the_row();
				    	 $name_top = get_term(get_sub_field('top_destination_location'));
				    	 $back_tax = get_field('hero_image', 'locations_'.$name_top->term_id); ?>
				    	 	<div>
				    	 		<a href="<?php echo get_term_link($name_top->term_id, 'locations'); ?>">
							  			<div class="table-box" style="background-image: url(<?php echo $back_tax; ?>)!important;">
								    		<div class="table-cell">
								    			<h3><?php echo $name_top->name; ?></h3>
								    		</div>
								    	</div>
								    </a>
							  </div>

				<?php	endwhile;
					endif;
				?>
			</div>
	</div>
</div>


<script>
	$("#s").attr("placeholder", "City or activity");
</script>
<?php get_footer(); ?>