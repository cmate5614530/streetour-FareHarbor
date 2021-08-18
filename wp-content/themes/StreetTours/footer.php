<footer>
      <div class="container-foundry">
      	<div class="row">
		    <div class="col-lg-3 col-md-3 col-sm-12">
		    	<div id="accor"><h4>Streetours</h4>
		    	<?php wp_nav_menu( array( 'theme_location' => 'streetours-menu' ) ); ?></div>
		    </div>
		    <div class="col-lg-3 col-md-3 col-sm-12">
		    	<div id="accor2"><h4>Popular Features</h4>
		    	<?php wp_nav_menu( array( 'theme_location' => 'features-menu' ) ); ?></div>
		    </div>
		    <div class="col-lg-3 col-md-3 col-sm-12">
		    	<div id="accor3"><h4>Work with us</h4>
		    	<?php wp_nav_menu( array( 'theme_location' => 'work-us-menu' ) ); ?></div>
		    </div>
		    <div class="col-lg-3 col-md-3 col-sm-12">
		    	<div id="accor4">
		    		<h4>Help</h4>
			    	<?php wp_nav_menu( array( 'theme_location' => 'help-menu' ) ); ?>
			    	<div class="pay">
			    		<?php 
			    			if( have_rows('ways_you_can_pay', 'option') ):
							    while( have_rows('ways_you_can_pay', 'option') ) : the_row(); ?>
							    	<img src="<?php echo get_sub_field('method_pay'); ?>">
						<?php		
								endwhile;
							endif;
			    		?>
			    	</div>
		    	</div>
		    </div>
		</div>

		  <div class="social-links">
		  	<h4>Follow us</h4>
		  	<a href="<?php echo get_field('facebook_link', 'option'); ?>" target="_blank"><img src="<?php echo get_template_directory_uri(); ?>/images/Facebook.png"></a>
		  	<a href="<?php echo get_field('linkedin_link', 'option'); ?>" target="_blank"><img src="<?php echo get_template_directory_uri(); ?>/images/Linkedin.png"></a>
		  	<a href="<?php echo get_field('twitter_link', 'option'); ?>" target="_blank"><img src="<?php echo get_template_directory_uri(); ?>/images/Twitter.png"></a>
		  	<a href="<?php echo get_field('instagram_link', 'option'); ?>" target="_blank"><img src="<?php echo get_template_directory_uri(); ?>/images/Instagram.png"></a>
		  	<a href="<?php echo get_field('youtube_link', 'option'); ?>" target="_blank"><img src="<?php echo get_template_directory_uri(); ?>/images/Youtube.png"></a>
		  	<a href="<?php echo get_field('tiktok_link', 'option'); ?>" target="_blank"><img src="<?php echo get_template_directory_uri(); ?>/images/Tiktok.png"></a>
		  	<a href="<?php echo get_field('pinterest_link', 'option'); ?>" target="_blank"><img src="<?php echo get_template_directory_uri(); ?>/images/Pinterest.png"></a>
		  </div>

		  <div class="last-section">
		  	<a href="<?php echo home_url(); ?>"><img src="<?php echo get_template_directory_uri(); ?>/images/Streetours-vector.svg"></a>
		  	<p>Copyright <?php echo date("Y"); ?> Streetours</p>
		  	<p><a href="<?php echo get_the_permalink(138); ?>">Legal Stuff</a>&nbsp;&nbsp;|&nbsp;&nbsp;<a href="<?php echo get_the_permalink(3); ?>">Privacy Policy</a></p>
		  </div>
      </div>
</footer>

	<script type="text/javascript" src="//code.jquery.com/jquery-migrate-1.2.1.min.js"></script>
 	<script type="text/javascript" src="<?php echo get_template_directory_uri(); ?>/slick/slick/slick.min.js"></script>

 	<script>
 		var $jq = jQuery.noConflict();
		$jq(document).ready(function() { 
		 
		  $jq('.slide-destinations').slick({
		  	    infinite: true,
  				slidesToShow: 4,
  				slidesToScroll: 1,
  				dots: false,
  				arrows: true,
  				autoplay: true,
  				autoplaySpeed: 1500,
  				speed: 1500,
  				responsive: [
			    {
			      	breakpoint: 1000,
			      	settings: {
			        slidesToShow: 3,
			      }
			    },
			    {
			    	breakpoint: 800,
			      	settings: {
			        slidesToShow: 1,
			      }
			    },
			    {
			    	breakpoint: 600,
			      	settings: {
			        slidesToShow: 1,
			      }
			    }
			    ]
			});

		  	$jq('.gallery-responsive').slick({
			  infinite: true,
			  slidesToShow: 1,
			  slidesToScroll: 1,
			  dots: true,
  			  arrows: true,
  			  prevArrow:"<img style='height: 30px!important;' class='a-left control-c prev slick-prev' src='http://streetours.com/wp-content/themes/StreetTours/images/l-arrow.svg'>",
    		  nextArrow:"<img style='height: 30px!important;' class='a-right control-c next slick-next' src='http://streetours.com/wp-content/themes/StreetTours/images/r-arrow.svg'>"
			});

			$jq('.all-view-gallery').slick({
			  infinite: true,
			  slidesToShow: 1,
			  slidesToScroll: 1,
			  dots: true,
  			  arrows: true,
  			  prevArrow:"<img class='a-left control-c prev slick-prev' src='http://streetours.com/wp-content/themes/StreetTours/images/l-arrow.svg'>",
    		  nextArrow:"<img class='a-right control-c next slick-next' src='http://streetours.com/wp-content/themes/StreetTours/images/r-arrow.svg'>"
			});

		});
 	</script>

 	<script>
 		$( "#accor, #accor2, #accor3, #accor4" ).click(function() {
 			if($(window).width() <= 900){
			  if ($( this ).hasClass( "on" )) {
			  	$( this ).removeClass( "on" );
			  	$( this ).find( "ul" ).css( "display", "none" );
			  	$( this ).find( "pay" ).css( "display", "none" );
			  	
			  }else{
			  	$( this ).addClass( "on" );
			  	$( this ).find( "ul" ).css( "display", "block" );
			  	$( this ).find( "pay" ).css( "display", "block" );
			  }
			}
		});
 	</script>

 	<script>
 		$( "#btn-hamburger-close" ).click(function() {
 			$( '.content-menu' ).removeClass( "act" );
 		});

 		$( "#btn-hamburger" ).click(function() {
		  if ($( this ).hasClass( "act" )) {
		  	//$( this ).removeClass( "act" );
		  	$( '.content-menu' ).removeClass( "act" );
		  }else{
		  	//$( this ).addClass( "act" );
		  	$( '.content-menu' ).addClass( "act" );
		  }
		});
 	</script>

    <?php wp_footer(); ?>

  </body>
</html>