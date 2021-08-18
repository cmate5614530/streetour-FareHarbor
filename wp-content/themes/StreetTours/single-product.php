<?php get_header(); 

$product = wc_get_product( get_the_ID() );
$time = wc_get_product_tag_list( $product->get_id(), ', ' );
$term_obj_list = get_the_terms( get_the_ID(), 'product_cat' );
$current_post = get_the_ID();

$duration = get_field('duration');
?>
<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
  <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
  <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
  <script src="js/jquery.image-popup.js"></script>

<div id="imageGallery" class="gallery-responsive">
	<?php 
		if( have_rows('gallery') ):
                while( have_rows('gallery') ) : the_row(); ?>
                      <div>
                        <a>
                          <img class="image-link" src="<?php echo get_sub_field('image'); ?> ">
                        </a>
                      </div>
                	
    <?php    	endwhile;
        endif;
	?>
</div>
<div class="show">
  <div class="overlay"></div>
  <div class="img-show">
    <span>X</span>
    <img src="">
  </div>
</div>

<script>
   $(function () {
    "use strict";
    
    $(".gallery-responsive img").click(function () {
        var $src = $(this).attr("src");
        $(".show").fadeIn();
        $(".img-show img").attr("src", $src);
    });
    
    $("span, .overlay").click(function () {
        $(".show").fadeOut();
    });
    
});
</script>


<div class="collage">
  <div class="container">
    
  <div class="breadcrumb-location">
    <?php 
      $arrayBread = array();
       $location = 0;
       $terms = get_the_terms( get_the_ID(), 'locations' );
       foreach( $terms as $key => $value){
        $location = $value->term_id;
        $locationGlobal = $value->term_id;


       }

       while($location != 0){
          $loc = get_term_by('id', $location, 'locations');
          $location = $loc->parent;
          $arrayBread[] = array($loc->name, get_term_link($loc->term_id, 'locations'));
         
       }

       $arrayBread= array_reverse($arrayBread);

        $index = 0;
       foreach( $arrayBread as $breadcrumb){
         if( $index == 0){
            echo '<a href="'.$breadcrumb[1].'">'.$breadcrumb[0].'</a>';

         }else{
           
            echo '<img style="height: 18px!important;" src="'.get_template_directory_uri().'/images/breadcrumb.svg">
            <a href="'.$breadcrumb[1].'">'.$breadcrumb[0].'</a>';
         }
         $index ++;

       }


    ?>
     
      
    </div>

  
    <div class="row"> 
        <?php
            if( have_rows('gallery') ):
                while( have_rows('gallery') ) : the_row(); 
                    if (get_row_index() == 3) { 
                      $rows = get_field('gallery' ); // get all the rows
                      $specific_row = $rows[2];
                      $img1 = $specific_row['image'];
                      $specific_row2 = $rows[3];
                      $img2 = $specific_row2['image'];

                      ?>
                        <div class="col-lg-3 col-md-3 col-sm-3 two-images">
                          <img class="img-2" src="<?php echo $img1; ?> ">
                          <img src="<?php echo $img2; ?> ">
                        </div>
                      <?php }elseif (get_row_index() == 4) {
                        
                      }elseif (get_row_index() >= 6) {
                      } else{ ?>
                        <div class="col-lg-3 col-md-3 col-sm-3">
                          <img class="img-<?php echo get_row_index(); ?>" src="<?php echo get_sub_field('image'); ?> ">
                        </div>
                    <?php } ?>
        <?php   endwhile;
            else: ?>
              <img class="img-complete" src="<?php echo get_the_post_thumbnail_url( $post_id, 'full' ); ?>">
      <?php endif;  
        ?>
    </div>
    <div id="btn-view-gallery"><svg width="14" height="14" viewBox="0 0 14 14" fill="none" xmlns="http://www.w3.org/2000/svg">
        <path d="M14 14H8.4V8.4H14V14ZM5.6 14H0V8.4H5.6V14ZM14 5.6H8.4V0H14V5.6ZM2.8 5.6C1.2536 5.6 0 4.3464 0 2.8C0 1.2536 1.2536 0 2.8 0C4.3464 0 5.6 1.2536 5.6 2.8C5.6 3.54261 5.305 4.2548 4.7799 4.7799C4.2548 5.305 3.54261 5.6 2.8 5.6Z" fill="#2E3A59"/>
        </svg>View more</div>

    <div class="shows">
  <div class="overlays"></div>
  <div class="img-shows">
    <span class="close-p">X</span>
    <img src="">
  </div>
</div>

<script>
   $(function () {
    "use strict";
    
    $(".collage img").click(function () {
        var $src = $(this).attr("src");
        $(".shows").fadeIn();
        $(".img-shows img").attr("src", $src);
    });
    
    $(".close-p, .overlays").click(function () {
        $(".shows").fadeOut();
    });
    
});
</script>


  </div>
</div>

<div class="container">
  <div class="view-gallery">
    <div class="modal-gallery">
      <span style="position: absolute;cursor: pointer; right: 30%;z-index: 99999;" id="close-gall">X</span>
        <div class="all-view-gallery">
          <?php 
            if( have_rows('gallery') ):
                        while( have_rows('gallery') ) : the_row(); ?>
                          <div>
                            <img src="<?php echo get_sub_field('image'); ?> ">
                          </div>
            <?php     endwhile;
                endif;
          ?>
        </div>
    </div>
  </div>
</div>

<script>
  $( "#btn-view-gallery" ).click(function() {
    if ($( this ).hasClass( "act-gall" )) {
        $( this ).removeClass( "act-gall" );
    }else{
        $( this ).addClass( "act-gall" );
        $( '.view-gallery' ).addClass( "act-gall" );
    }
  });

  $( "#close-gall" ).click(function() {
    $( '.view-gallery' ).removeClass( "act-gall" );
  });
</script>


<div class="container">
	<div class="header-location">
        <span class="type-tour type-responsive"><?php echo $terms_string = join(', ', wp_list_pluck($term_obj_list, 'name')); ?></span>
		<h3><?php echo get_the_title(); ?></h3>

    <div class="price-columns">
      <div class="row">
        <div class="col-8">
          <h5> <?php echo start_info(); ?> </h5>
        </div>
        <div class="col-4">
          <h4>‎£ <?php echo $product->get_regular_price(); ?></h4>
        </div>
      </div>
    </div>

		<h4><?php echo get_the_excerpt(); ?></h4>
	</div>

	<div class="all-content">
		<div class="row">
		    <div class="col-8 col-lg-8 col-md-8 col-sm-12">
		    	<div class="grey-line"></div>
          <ul>
            <?php
              if( have_rows('tour_options') ):
                  while( have_rows('tour_options') ) : the_row(); 
                    if (get_sub_field('best_price')[0] == "Yes") {
                      echo '<li><img src="'.get_template_directory_uri().'/images/blue-flag.svg">Best Price Guarantee</li>';
                    }
                    if ($duration) {
                      echo '<li><img src="'.get_template_directory_uri().'/images/blue-time.svg">Duration: '.$duration.' hours</li>';
                    }
                    if (get_sub_field('free_cancellation')[0] == "Yes") {
                      echo '<li><img src="'.get_template_directory_uri().'/images/blue-cancel.svg">Free Cancellation</li>';
                    }
                    if (get_sub_field('language')) {
                      echo '<li><img src="'.get_template_directory_uri().'/images/blue-language.svg">Language: '.get_sub_field('language').'</li>';
                    }
                    if (get_sub_field('covid_19_precaution')) {
                      echo '<li><img src="'.get_template_directory_uri().'/images/pink-restrictions.svg">Covid 19 Precaution: '.get_sub_field('covid_19_precaution').'</li>';
                    }
                  endwhile;
              endif;
            ?>
		    	</ul>

		    	<div class="grey-line"></div>

		    	<h3>Description</h3>

		    	<p><?php echo get_the_content(); ?></p>

				  <div class="grey-line"></div>

          <h3>Higlights</h3>
          <div class="bullet-points">
            <?php echo get_field('higlights'); ?>
          </div>

				  <div class="grey-line"></div>

				  <h3>Details</h3>
          <ul class="details">
              <?php 
                if( have_rows('details') ):
                    while( have_rows('details') ) : the_row();  
                      if (get_sub_field('activity_provider')) {
                        echo '<li><img src="'.get_template_directory_uri().'/images/Information-Icon.svg">Activity Provider:&nbsp; '.get_sub_field('activity_provider').'</li>';
                      } 
                      if (get_sub_field('language')) {
                          echo '<li><img src="'.get_template_directory_uri().'/images/Langugage-Icon.svg">Language: '.get_sub_field('language').'</li>';
                        }
                        if (get_sub_field('includes')) {
                          echo '<li><img src="'.get_template_directory_uri().'/images/Incluces-Icon.svg">Includes:&nbsp; '.get_sub_field('includes').'</li>';
                        }
                        if (get_sub_field('ticketing')) {
                          echo '<li><img src="'.get_template_directory_uri().'/images/Phone-Icon.svg">'.get_sub_field('ticketing').'</li>';
                        }
                        if (get_sub_field('confirmation')) {
                          echo '<li><img src="'.get_template_directory_uri().'/images/Instant-Icon.svg">'.get_sub_field('confirmation').'</li>';
                        }
                        if ($duration) {
                          echo '<li><img src="'.get_template_directory_uri().'/images/Duration-Icon.svg">'.$duration.' hours duration</li>';
                        }
                        if (get_sub_field('groups_types')) {
                          echo '<li><img src="'.get_template_directory_uri().'/images/Small-group-Icon.svg">'.get_sub_field('groups_types').'</li>';
                        }
                        if (get_sub_field('activity_type')) {
                          echo '<li><img src="'.get_template_directory_uri().'/images/Family-Icon.svg">'.get_sub_field('activity_type').'</li>';
                        }
                    endwhile;
                endif;
              ?>
          </ul>

		    	<div class="grey-line"></div>

		    </div>
		    <div class="col-4 col-lg-4 col-md-4 col-sm-12">
		    	<div class="sidebar">
		    		<?php echo get_field('turitop_iframe'); ?>
		    	</div>
		    </div>
		</div>

		<h3>Meeting Point</h3>
		<?php echo get_field('location_map'); ?>
		<p class="info-map"><?php echo get_field('description_location'); ?></p>
		<div class="grey-line"></div>


    <div class="comments">
				  <h5 style="margin-bottom: 0!important;"><?php echo start_info(); ?> </h5>
          <?php if ( comments_open() || get_comments_number() ) :
                    comments_template();
                endif; 
          ?> 
    </div>



	

		    <div class="similar">
		    	<h3>Similar activities</h3>
                    <div class="boxes-explore">
                        <div class="row">
                                    <?php   
                                            $args = array(
                                            'post_type' => 'product',
                                            'posts_per_page' => 8,
                                            'post__not_in'           => array($current_post),
                                            'tax_query' => array(
                                                array (
                                                    'taxonomy' => 'locations',
                                                    'field' => 'term_id',
                                                    'terms' => $locationGlobal,
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
                                            wp_reset_postdata();
                                        endif;
                                        ?>
                        </div>
                    </div>
		    </div>
	</div>

    <!--Responsive content-->
            <div class="all-content all-content-responsive">
              <div class="row">
                  <div class="col-8 col-lg-8 col-md-8 col-sm-12">

                      <ul>
                        <?php
                          if( have_rows('tour_options') ):
                              while( have_rows('tour_options') ) : the_row(); 
                                if (get_sub_field('best_price')[0] == "Yes") {
                                  echo '<li><img src="'.get_template_directory_uri().'/images/blue-flag.svg">Best Price Guarantee</li>';
                                }
                                if ($duration) {
                                  echo '<li><img src="'.get_template_directory_uri().'/images/blue-time.svg">Duration: '.$duration.' hours</li>';
                                }
                                if (get_sub_field('free_cancellation')[0] == "Yes") {
                                  echo '<li><img src="'.get_template_directory_uri().'/images/blue-cancel.svg">Free Cancellation</li>';
                                }
                                if (get_sub_field('language')) {
                                  echo '<li><img src="'.get_template_directory_uri().'/images/blue-language.svg">Language: '.get_sub_field('language').'</li>';
                                }
                                if (get_sub_field('covid_19_precaution')) {
                                  echo '<li><img src="'.get_template_directory_uri().'/images/pink-restrictions.svg">Covid 19 Precaution: '.get_sub_field('covid_19_precaution').'</li>';
                                }
                              endwhile;
                          endif;
                        ?>
                      </ul>



                      <div id="accordion">
                        <h3 class="aco">Description</h3>
                        <div class="pan">
                          <p class="all-text"><?php echo get_the_content(); ?></p>
                          <a id="read-all-text" href="javascript:void(0)">Read more</a>
                        </div>
                        <h3 class="aco">Highlights</h3>
                        <div class="pan">
                          <div class="bullet-points">
                            <?php echo get_field('higlights'); ?>
                          </div>
                        </div>
                        <h3 class="aco">Details</h3>
                        <div class="pan">
                          <ul class="details responsivedetails">
                            <?php 
                              if( have_rows('details') ):
                                  while( have_rows('details') ) : the_row();  
                                    if (get_sub_field('activity_provider')) {
                                      echo '<li><img src="'.get_template_directory_uri().'/images/Information-Icon.svg">Activity Provider:&nbsp; '.get_sub_field('activity_provider').'</li>';
                                    } 
                                    if (get_sub_field('language')) {
                                        echo '<li><img src="'.get_template_directory_uri().'/images/Langugage-Icon.svg">Language: '.get_sub_field('language').'</li>';
                                      }
                                      if (get_sub_field('includes')) {
                                        echo '<li><img src="'.get_template_directory_uri().'/images/Incluces-Icon.svg">Includes:&nbsp; '.get_sub_field('includes').'</li>';
                                      }
                                      if (get_sub_field('ticketing')) {
                                        echo '<li><img src="'.get_template_directory_uri().'/images/Phone-Icon.svg">'.get_sub_field('ticketing').'</li>';
                                      }
                                      if (get_sub_field('confirmation')) {
                                        echo '<li><img src="'.get_template_directory_uri().'/images/Instant-Icon.svg">'.get_sub_field('confirmation').'</li>';
                                      }
                                      if ($duration) {
                                        echo '<li><img src="'.get_template_directory_uri().'/images/Duration-Icon.svg">'.$duration.' hours duration</li>';
                                      }
                                      if (get_sub_field('groups_types')) {
                                        echo '<li><img src="'.get_template_directory_uri().'/images/Small-group-Icon.svg">'.get_sub_field('groups_types').'</li>';
                                      }
                                      if (get_sub_field('activity_type')) {
                                        echo '<li><img src="'.get_template_directory_uri().'/images/Family-Icon.svg">'.get_sub_field('activity_type').'</li>';
                                      }
                                  endwhile;
                              endif;
                            ?>
                        </ul>
                        </div>
                        <h3 class="aco">Meeting Point</h3>
                        <div class="pan">
                          <?php echo get_field('location_map'); ?>
                          <p class="info-map"><?php echo get_field('description_location'); ?></p>
                        </div>
                        <h3 class="aco">Reviews</h3>
                          <div class="pan">     
                                
                                <div class="comments">
                                    <h5 style="margin-bottom: 0!important;"><?php echo start_info(); ?> </h5>
                                    <?php if ( comments_open() || get_comments_number() ) :
                                              comments_template();
                                          endif; 
                                    ?> 
                                    
                                </div> 
                        </div>
                      </div>

                      <div class="sidebar sidebar-responsive">
                        <?php echo do_shortcode('[turitop_booking_system product_id="P1" embed="box"]'); ?>
                      </div>

                      <div class="similar">
                            <h3>Similar activities</h3>
                            <div class="boxes-explore">
                                <div class="row">
                                              <?php 
                                                      $args = array(
                                                      'post_type' => 'product',
                                                      'posts_per_page' => 8,
                                                      'post__not_in'           => array($current_post),
                                                      'tax_query' => array(
                                                          array (
                                                              
                                                            'taxonomy' => 'locations',
                                                            'field' => 'term_id',
                                                            'terms' => $locationGlobal,
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
                                                  ?>
                                </div>
                            </div>
                      </div>
                  </div>
        </div>
    <!--End Responsive content-->

</div>
</div>
</div>

<script>
var acc = document.getElementsByClassName("aco");
var i;

for (i = 0; i < acc.length; i++) {
  acc[i].addEventListener("click", function() {
    this.classList.toggle("active");
    var panel = this.nextElementSibling;
    if (panel.style.display === "block") {
      panel.style.display = "none";
    } else {
      panel.style.display = "block";
    }
  });
}
</script>

<script>
    $( function() {
        jQuery('#accordion h3').accordion({
          //active: false, collapsible:true, active:true
      });
    } );

    $( "#read-all-text" ).click(function() {
      if ($( ".all-text" ).hasClass( "on" )) {
        $( ".all-text" ).removeClass( "on" );
        $( this ).text('Read more');
      }else{
        $( ".all-text" ).addClass( "on" );
        $( this ).text('Read less');
      }

      
    });

</script>
<?php get_footer(); ?>