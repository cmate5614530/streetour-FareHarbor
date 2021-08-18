<?php get_header(); ?>
<?php $tax = $wp_query->get_queried_object(); ?>

<?php
    if (get_field('hero_image', 'locations_'.$tax->term_id)) {
        $main_back = get_field('hero_image', 'locations_'.$tax->term_id);
    }else{
        $main_back = 'http://streetours.com/wp-content/uploads/2021/04/Rectangle-503-2.png';
    }
?>

<div class="location-banner">
    <div class="table-box" style="background-image: url(<?php echo $main_back; ?>)!important;">
        <div class="table-cell">
            <h1><?php echo $tax->name; ?></h1>
            <div class="search-tax">
                <form action="<?php echo get_the_permalink(368); ?>" id="search-form" method="get">
                    <input type="text" name="search-text-resp" id="" placeholder="Search destination or experience" />
                    <input type="submit" value=" " />
                </form>
            </div>
        </div>
    </div>
</div>
<?php 
	/*if ( $_POST['cat_filter']) {
		$cat_filter = $_POST['cat_filter']; 
	}else{
		$cat_filter = ''; 
	}
	
	echo $cat_filter;
	echo $service = $_POST['type_service'];
	echo $minprice = $_POST['min-price'];
	echo $maxprice = $_POST['max-price'];
	echo $minduration = $_POST['min-duration'];
	echo $maxduration = $_POST['max-duration'];*/
?>



<div id="top" class="filters">
	<div class="container">
		<form method="get" action="<?php echo get_the_permalink(368); ?>">
			<input type="hidden" id="taxid" name="taxid" value="<?php echo $tax->term_id; ?>">
			<div class="categories" style="overflow-x: auto;white-space: nowrap;">
                 <button type="button" class="checkall">
                    <svg fill="#00B5C7" version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
                         width="276.167px" height="276.167px" viewBox="0 0 276.167 276.167" style="enable-background:new 0 0 276.167 276.167;"
                         xml:space="preserve">
                    <g>
                        <g>
                            <path d="M33.144,2.471C15.336,2.471,0.85,16.958,0.85,34.765s14.48,32.293,32.294,32.293s32.294-14.486,32.294-32.293
                                S50.951,2.471,33.144,2.471z"/>
                            <path d="M137.663,2.471c-17.807,0-32.294,14.487-32.294,32.294s14.487,32.293,32.294,32.293c17.808,0,32.297-14.486,32.297-32.293
                                S155.477,2.471,137.663,2.471z"/>
                            <path d="M243.873,67.059c17.804,0,32.294-14.486,32.294-32.293S261.689,2.471,243.873,2.471s-32.294,14.487-32.294,32.294
                                S226.068,67.059,243.873,67.059z"/>
                            <path d="M32.3,170.539c17.807,0,32.297-14.483,32.297-32.293c0-17.811-14.49-32.297-32.297-32.297S0,120.436,0,138.246
                                C0,156.056,14.493,170.539,32.3,170.539z"/>
                            <path d="M136.819,170.539c17.804,0,32.294-14.483,32.294-32.293c0-17.811-14.478-32.297-32.294-32.297
                                c-17.813,0-32.294,14.486-32.294,32.297C104.525,156.056,119.012,170.539,136.819,170.539z"/>
                            <path d="M243.038,170.539c17.811,0,32.294-14.483,32.294-32.293c0-17.811-14.483-32.297-32.294-32.297
                                s-32.306,14.486-32.306,32.297C210.732,156.056,225.222,170.539,243.038,170.539z"/>
                            <path d="M33.039,209.108c-17.807,0-32.3,14.483-32.3,32.294c0,17.804,14.493,32.293,32.3,32.293s32.293-14.482,32.293-32.293
                                S50.846,209.108,33.039,209.108z"/>
                            <path d="M137.564,209.108c-17.808,0-32.3,14.483-32.3,32.294c0,17.804,14.487,32.293,32.3,32.293
                                c17.804,0,32.293-14.482,32.293-32.293S155.368,209.108,137.564,209.108z"/>
                            <path d="M243.771,209.108c-17.804,0-32.294,14.483-32.294,32.294c0,17.804,14.49,32.293,32.294,32.293
                                c17.811,0,32.294-14.482,32.294-32.293S261.575,209.108,243.771,209.108z"/>
                        </g>
                    </g>
                    <g>
                    </svg>
                 All categories</button>
                 <script>
                     var clicked = false;
                        $(".checkall").on("click", function() {
                            if ($( this ).hasClass( "on" )) {
                                $( this ).removeClass( "on" );
                            }else{
                                $( this ).addClass( "on" );
                            }
                          $(".checkhour").prop("checked", !clicked);
                          clicked = !clicked;
                          //this.innerHTML = clicked ? 'All categories' : 'All categories';
                        });
                 </script>
				<?php
					$taxonomy = "product_cat";
					$args_terms_id_list = array(23,24,27,28,29,30,31);

					foreach ($args_terms_id_list as $current_term_id){ 
						$taxonomy_terms = get_term_by("id", $current_term_id , $taxonomy); 
						$back = get_field('icon',$taxonomy_terms); 
						?>
						<div id="ck-button">
							<label>
								<input type="checkbox" class="checkhour" id="btn-cat" name="cat_filter[]" value="<?php echo $taxonomy_terms->term_id; ?>"><span><?php echo $back; ?><?php echo $taxonomy_terms->name; ?></span>
							</label>
						</div>
				<?php		 
					}
				?>
		        
		    </div>

			<div class="btn-filters">
				<a id="filter-btn" class="white-btn" href="javascript:void(0)">FILTER BY</a>
				<a id="sort-btn" class="white-btn" href="javascript:void(0)">SORT BY</a>
				<input id="send-filter" type="submit" name="FILTER" value="FILTER">
			</div>
			<div id="options">
				<div class="row">
				    <div class="col-6">
				    	<ul>
							<li><a class="white-btn sub_class" id="btn-service" href="javascript:void(0)">Services</a></li>
							<li><a class="white-btn sub_class" id="btn-price" href="javascript:void(0)">Price</a></li>
							<li><a class="white-btn sub_class" id="btn-duration" href="javascript:void(0)">duration</a></li>
						</ul>
				    </div>
				    <div class="col-6 sub_menu_class">
				    	<ul id="all-services">
				    		<?php 
					            $orderby = 'name';
						        $order = 'asc';
						        $hide_empty = false ;
						        $cat_args = array(
						            'orderby'    => $orderby,
						            'order'      => $order,
						            'hide_empty' => $hide_empty,
						        );
						         
						        $product_services = get_terms( 'services', $cat_args );
						         
						        if( !empty($product_services) ){ 
						            foreach ($product_services as $key => $categorys) {  $backs = get_field('icon',$categorys); ?>
						            	<input type="checkbox" id="<?php echo $categorys->name; ?>" name="type_service[]" value="<?php echo $categorys->term_id; ?>">
										<label for="<?php echo $categorys->name; ?>"><?php echo $categorys->name; ?></label><br>
						     <?php } 
						    } ?>
				    		
				    	</ul>
				    	<ul id="all-prices">
				    			<section class="range-slider">
								  <span class="rangeValues"></span>
								  <input value="0" min="0" max="120" step="1" name="min-price" type="range">
								  <input style="position: relative;top: -44px;" value="120" min="0" max="120" step="1" name="max-price" type="range">
								</section>
				    	</ul>
				    	<ul id="all-duration">
				    			<section class="range-sliderd">
								  <span class="rangeValuesd"></span>
								  <input value="1" min="1" max="120" step="1" name="min-duration" type="range">
								  <input style="position: relative;top: -44px;" value="120" min="1" max="120" step="1" name="max-duration" type="range">
								</section>
				    	</ul>
				    </div>
				  </div>
				
			</div>

			<div id="options-sort">
				<ul id="all-options">
				    		<li><label class="container">Recommended
								  <input type="radio" name="sort" value="Recommended">
								  <span class="checkmark"></span>
								</label>
							</li>
							<li><label class="container">Price: Low to High
								  <input type="radio" name="sort" value="Price: Low to High">
								  <span class="checkmark"></span>
								</label>
							</li>
							<li><label class="container">Price: High to Low
								  <input type="radio" name="sort" value="Price: High to Low">
								  <span class="checkmark"></span>
								</label>
							</li>
				    	</ul>
			</div>
				
		</form>
	</div>
</div>

<script>
	$( "#filter-btn" ).click(function() {
		if ($( this ).hasClass( "on-active" )) {
			$( this ).removeClass( "on-active" );
			$("#options").removeClass("on-active");
		}else{
			$( this ).addClass( "on-active" );
			$("#options").addClass("on-active");
			$("#options-sort").removeClass("on-active");
			$("#sort-btn").removeClass("on-active");
		}
	});

	$( "#sort-btn" ).click(function() {
		if ($( this ).hasClass( "on-active" )) {
			$( this ).removeClass( "on-active" );
			$("#options-sort").removeClass("on-active");
		}else{
			$( this ).addClass( "on-active" );
			$("#options-sort").addClass("on-active");
			$("#options").removeClass("on-active");
			$("#filter-btn").removeClass("on-active");
		}
	});
</script>
<script>
	$( ".sub_class" ).click(function() {
		var option = $(this).prop('id');
		if (option == "btn-service") {
			$("#all-services").addClass("on-active");
			$( "#all-prices" ).removeClass("on-active");
			$( "#all-duration" ).removeClass("on-active");
		}else if(option == "btn-price"){
			$("#all-services").removeClass("on-active");
			$( "#all-prices" ).addClass("on-active");
			$( "#all-duration" ).removeClass("on-active");
		}else if(option == "btn-duration"){
			$("#all-services").removeClass("on-active");
			$( "#all-prices" ).removeClass("on-active");
			$( "#all-duration" ).addClass("on-active");
		}
	});
</script>

<div class="top-xp">
    <div class="container">
        <h3>Explore <?php echo $tax->name; ?></h3>
        <div class="boxes-tour">
        	<?php echo do_shortcode('[ajax_load_more post_type="product" posts_per_page="6" taxonomy="locations" taxonomy_terms="'.$tax->name.'" taxonomy_operator="IN:IN:IN" button_label="View More"]'); ?>
<!--            --><?php
//                $args = array(
//                    'post_type' => 'product',
//                    'posts_per_page' => 6,
//                    'tax_query' => array(
//                    	'relation' => 'AND',
//                        array(
//                            'taxonomy' => 'locations',
//                            'field'    => 'slug',
//                            'terms'    => array( $tax->slug ),
//                            'operator' => 'IN'
//                        ),
//                        array(
//                            'taxonomy' => 'product_cat',
//                            'field'    => 'slug',
//                            'terms'    => array( $cat_filter ),
//                            'operator' => 'IN'
//                        ),
//                    ),
//                );
//                $arr_posts = new WP_Query( $args );
//
//                if ( $arr_posts->have_posts() ) :
//
//                    while ( $arr_posts->have_posts() ) :
//                        $arr_posts->the_post();
//                        ?>
<!--                            --><?php //echo get_template_part("components/experience","box"); ?>
<!--                        --><?php
//                    endwhile;
//                    wp_reset_postdata();
//                endif;
//            ?>


        </div>
    </div>
</div>

<div class="grey-banner">
    <div class="container">
        <div class="items">
            <div class="row">
                <div class="col-4"><p><img src="<?php echo get_template_directory_uri(); ?>/images/pink-flag.svg">Best Price Guarantee</p></div>
                <div class="col-4"><p><img src="<?php echo get_template_directory_uri(); ?>/images/pink-heart.svg">Verified Reviews</p></div>
                <div class="col-4"><p><img src="<?php echo get_template_directory_uri(); ?>/images/pink-check.svg">Free Cancellation</p></div>
            </div>
        </div>
    </div>
</div>


<div class="explore-london">
    <div class="container">
        <h3>Top Experiences in <?php echo $tax->name; ?></h3>
        <div class="boxes-explore">
            <div class="row">
<!--                        --><?php //
//                            if( have_rows('top_experience', 'locations_'.$tax->term_id) ):
//                                while( have_rows('top_experience', 'locations_'.$tax->term_id) ) : the_row();
//                                    $id_plan = get_sub_field('plan'); ?>
<!--                                        --><?php //
//                                    $product = wc_get_product( $id_plan );
//                                    $time = wc_get_product_tag_list( $product->get_id(), ', ' );
//                                    $term_obj_list = get_the_terms( $id_plan, 'product_cat' );
//                                    ?>
<!--                                                 <div class="col-lg-3 col-md-3 col-sm-6 col-xs-6">-->
<!--                                                <div class="box-explore">-->
<!--                                                    <a href="--><?php //echo get_the_permalink($id_plan); ?><!--">-->
<!--                                                    <img style="width: 100%;height: 116px;object-fit: cover;" src="--><?php //echo get_the_post_thumbnail_url($id_plan);?><!--">-->
<!--                                                    </a>-->
<!--                                                    <div class="content-explore">-->
<!--                                                        <span class="type-tour">--><?php //echo $terms_string = join(', ', wp_list_pluck($term_obj_list, 'name')); ?><!--</span>-->
<!--                                                        <span class="time-tour">--><?php //echo $time; ?><!--</span>-->
<!--                                                        <a href="--><?php //echo get_the_permalink($id_plan); ?><!--"><h4>--><?php //echo get_the_title($id_plan); ?><!--</h4></a>-->
<!--                                                        <div class="row">-->
<!--                                                            <div class="col-8">-->
<!--                                                                <h5 style="margin-bottom: 0!important;">4.7/5 -->
<!--                                                                    <img src="--><?php //echo get_template_directory_uri(); ?><!--/images/star-icon.svg">-->
<!--                                                                    <img src="--><?php //echo get_template_directory_uri(); ?><!--/images/star-icon.svg">-->
<!--                                                                    <img src="--><?php //echo get_template_directory_uri(); ?><!--/images/star-icon.svg">-->
<!--                                                                    <img src="--><?php //echo get_template_directory_uri(); ?><!--/images/star-icon.svg">-->
<!--                                                                    <img src="--><?php //echo get_template_directory_uri(); ?><!--/images/star-icon.svg">-->
<!--                                                                </h5>-->
<!--                                                            </div>-->
<!--                                                            <div class="col-4">-->
<!--                                                                <h2>‎£ --><?php //echo $product->get_price(); ?><!--</h2>-->
<!--                                                            </div>-->
<!--                                                          </div>-->
<!--                                                    </div>-->
<!--                                                </div>-->
<!--                                            </div>-->
<!---->
<!--                        --><?php //  endwhile;
//                         wp_reset_postdata();
//                            endif;
//                        ?>

                       
            </div>
        </div>
    </div>
</div>

<div class="best-spot">
    <h3 class="title">Best spots in <?php echo $tax->name; ?></h3>
      <div class="row">
                <?php 
                            $args = array(
                                'post_type' => 'spots',
                                'posts_per_page' => 2,
                                'order' => 'DESC',
                                'tax_query' => array(
                                    array(
                                        'taxonomy' => 'locations',
                                        'field'    => 'slug',
                                        'terms'    => array( $tax->slug ),
                                        'operator' => 'IN'
                                    ),
                                ),
                            );
                            $arr_posts = new WP_Query( $args );
                            $main_spot = array();
                            $cont_post = 1;
                             
                            if ( $arr_posts->have_posts() ) :
                             
                                while ( $arr_posts->have_posts() ) :
                                    $arr_posts->the_post();
                                    array_push($main_spot, get_the_ID());
                                    if ($cont_post == 1) { ?>
                                        <div class="col-lg-8 col-md-8 col-sm-12">
                                            <div class="spot-box" style="margin-right: 10px;">
                                                <div class="table-box" style="background-image: url(<?php echo get_the_post_thumbnail_url( get_the_ID(), 'full' ); ?>) !important;">
                                                    <div class="table-cell">
                                                        <h3><?php echo get_the_title(); ?></h3>
                                                        <?php if (get_field('travel_guide_link')) { ?>
                                                            <a class="white-btn" href="<?php echo get_field('travel_guide_link'); ?>">Travel Guide</a>
                                                        <?php } ?>
                                                        <?php if (get_field('book_activities_link')) { ?>
                                                             <a class="white-btn" href="<?php echo get_field('book_activities_link'); ?>">Book activities</a>
                                                        <?php } ?>
                                                        
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                              <?php 
                                    $cont_post++;
                                    }elseif ($cont_post == 2) { ?>
                                        <div class="col-lg-4 col-md-4 col-sm-12">
                                            <div class="spot-box" style="margin-left: 10px;">
                                                <div class="table-box" style="background-image: url(<?php echo get_the_post_thumbnail_url( get_the_ID(), 'full' ); ?>) !important;">
                                                    <div class="table-cell">
                                                        <h3><?php echo get_the_title(); ?></h3>
                                                         <?php if (get_field('travel_guide_link')) { ?>
                                                            <a class="white-btn" href="<?php echo get_field('travel_guide_link'); ?>">Travel Guide</a>
                                                        <?php } ?>
                                                        <?php if (get_field('book_activities_link')) { ?>
                                                             <a class="white-btn" href="<?php echo get_field('book_activities_link'); ?>">Book activities</a>
                                                        <?php } ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                              <?php }
                                endwhile;
                                wp_reset_postdata();
                            endif;
                            ?>
      </div>

      <div class="row second-row">
        <?php 
                $args = array(
                'post_type' => 'spots',
                'post__not_in' => $main_spot,
                'posts_per_page' => 3,
                'order' => 'DESC',
                'tax_query' => array(
                        array(
                            'taxonomy' => 'locations',
                            'field'    => 'slug',
                            'terms'    => array( $tax->slug ),
                            'operator' => 'IN'
                        ),
                ),

            );
            $arr_posts = new WP_Query( $args );
            $main_spot = array();
             
            if ( $arr_posts->have_posts() ) :
             
                while ( $arr_posts->have_posts() ) :
                    $arr_posts->the_post();
                    array_push($main_spot, get_the_ID());
                    ?>
                        <div class="col-lg-4 col-md-4 col-sm-12">
                            <div class="spot-box">
                                <div class="table-box" style="background-image: url(<?php echo get_the_post_thumbnail_url( get_the_ID(), 'full' ); ?>) !important;">
                                    <div class="table-cell">
                                        <h3><?php echo get_the_title(); ?></h3>
                                         <?php if (get_field('travel_guide_link')) { ?>
                                                            <a class="white-btn" href="<?php echo get_field('travel_guide_link'); ?>">Travel Guide</a>
                                                        <?php } ?>
                                                        <?php if (get_field('book_activities_link')) { ?>
                                                             <a class="white-btn" href="<?php echo get_field('book_activities_link'); ?>">Book activities</a>
                                                        <?php } ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php
                endwhile;
                 wp_reset_postdata();
            endif;
            ?>
      </div>

</div>

<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.6/jquery.min.js" type="text/javascript"></script>
<script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/jquery-ui.min.js" type="text/javascript"></script>
<link href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/themes/base/jquery-ui.css" rel="Stylesheet" type="text/css" />

<script type='text/javascript'>
var $j = jQuery.noConflict();
$(document).ready(function(){
 
 // Initialize Date picker 
 $j("#startdate, #enddate").datepicker({
  dateFormat: "yy-mm-dd"
 });
});
</script>
<div class="blue-banner">
    <h3>Save on your next trip in <?php echo $tax->name; ?>!</h3>
    <p>Receive exclusive discounts by email.</p>
    <?php  echo do_shortcode('[contact-form-7 id="38" title="Disscount"]'); ?>
</div>


<div class="things-to-do">
    <div class="container">
        <h3>Best things to do in <?php echo $tax->name; ?></h3>

        <p class="description">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc in nisi ipsum. Proin non ipsum vel neque pretium laoreet id vel ante. </p>

        <div class="row">
            <?php 
                    $args = array(
                    'post_type' => 'things-to-do',
                    'posts_per_page' => 10,
                    'order' => 'DESC',
                    'tax_query' => array(
                        array(
                            'taxonomy' => 'locations',
                            'field'    => 'slug',
                            'terms'    => array( $tax->slug ),
                            'operator' => 'IN'
                        ),
                    ),
                );
                $arr_posts = new WP_Query( $args );
                $cont_things = 1;
                 
                if ( $arr_posts->have_posts() ) :
                 
                    while ( $arr_posts->have_posts() ) :
                        $arr_posts->the_post();
                        ?>
                            <div class="col-lg-6 col-md-6 col-sm-12">
                                <a href="<?php echo get_the_permalink(); ?>">
                                    <div class="box-things">
                                        <img src="<?php echo get_the_post_thumbnail_url( get_the_ID(), 'full' ); ?>">
                                        <h4><?php echo $cont_things; ?>. <?php echo get_the_title(); ?></h4>
                                        <p><?php echo get_the_excerpt(); ?></p>
                                    </div>
                                </a>
                            </div>
                        <?php
                        $cont_things++;
                    endwhile;
                endif;
                ?>
        </div>
    </div>
</div>

<?php echo get_template_part("components/offers","box"); ?>
<?php echo get_template_part("components/offers","boxresponsive"); ?>

<?php 
    $queried_object = get_queried_object(); 
    $taxonomy = $queried_object->taxonomy;
    $term_id = $queried_object->term_id;
    $type_style = get_field('type_show_plan_steps', $taxonomy . '_' . $term_id);
?>

<?php if ($type_style != 'Linear style') { ?>

<div class="plan">
    <div class="container">
        <h3>How to plan your <?php echo $tax->name; ?> trip</h3>

        <p class="description"><?php echo get_field('how_to_plan_description' , $taxonomy . '_' . $term_id); ?></p>

        <?php 
            $plan_note = get_field('plan_note', $taxonomy . '_' . $term_id);
            $plan_info = get_field('plan_note_information', $taxonomy . '_' . $term_id);
        ?>

        <div class="row">
            <?php 
            if( have_rows('plan_steps', $taxonomy . '_' . $term_id) ):
                while( have_rows('plan_steps', $taxonomy . '_' . $term_id) ) : the_row(); 
                    if (get_row_index() == 1) { ?>
                        <div class="col-4 col-lg-4 col-md-4 col-sm-12">
                            <div class="plan-box first-box">
                                <h4 class="step">1</h4>
                                <h5><?php echo get_sub_field('plan_name'); ?></h5>
                                <?php echo get_sub_field('plan_description'); ?>
                            </div>
                        </div>
                    <?php 
                    }elseif (get_row_index() == 2) { ?>
                        <div class="col-4 col-lg-4 col-md-4 col-sm-12">
                            <div class="plan-box second-box">
                                <h4 class="step">2</h4>
                                <h5><?php echo get_sub_field('plan_name'); ?></h5>
                                <?php echo get_sub_field('plan_description'); ?>
                            </div>
                        </div>
                    <?php
                    }elseif (get_row_index() == 3) { ?>
                        <div class="col-4 col-lg-4 col-md-4 col-sm-12">
                            <div class="plan-box third-box">
                                <h4 class="step">3</h4>
                                <h5><?php echo get_sub_field('plan_name'); ?></h5>
                                <?php echo get_sub_field('plan_description'); ?>
                            </div>
                        </div>
                    <?php
                    }elseif (get_row_index() == 4) { ?>
                        <div class="col-8 col-lg-8 col-md-8 col-sm-12">
                            <div class="info-plan">
                                <h4><?php echo $plan_note; ?></h4>
                                <p><?php echo $plan_info; ?></p>
                            </div>
                        </div>
                        <div class="col-4 col-lg-4 col-md-4 col-sm-12">
                            <div class="plan-box fourth-box">
                                <h4 class="step">4</h4>
                                <h5><?php echo get_sub_field('plan_name'); ?></h5>
                                <?php echo get_sub_field('plan_description'); ?>
                            </div>
                        </div>

                    <?php } ?>

        <?php   endwhile;
            endif;
        ?>
        </div>

        

    </div>
</div>
<?php }else{ ?>
<!--Linear style-->
<div id="linear-style" class="plan">
    <div class="container">
        <h3>How to plan your <?php echo $tax->name; ?> trip</h3>
        <p class="description"><?php echo get_field('how_to_plan_description' , $taxonomy . '_' . $term_id); ?></p>

        <?php 
            $plan_note = get_field('plan_note', $taxonomy . '_' . $term_id);
            $plan_info = get_field('plan_note_information', $taxonomy . '_' . $term_id);
        ?>
        <div class="line-step"></div>
        <div class="row">
            <?php
                if( have_rows('plan_steps', $taxonomy . '_' . $term_id) ):
                    while( have_rows('plan_steps', $taxonomy . '_' . $term_id) ) : the_row(); ?>
                        <div class="col-3 col-lg-3 col-md-3 col-sm-12">
                            <div class="plan-box">
                                <h4 class="step"><?php echo get_row_index(); ?></h4>
                                <h5><?php echo get_sub_field('plan_name'); ?></h5>
                                <?php echo get_sub_field('plan_description'); ?>
                            </div>
                        </div>
            <?php   endwhile;
                endif;
            ?>
        </div>

         <div class="info-plan">
            <h4><?php echo $plan_note; ?></h4>
            <div class="small-line"></div>
            <p><?php echo $plan_info; ?></p>
        </div>

    </div>
</div>
<!--End Linear style-->
<?php } ?>

<div class="plan plan-responsive">
    <div class="container">
        <h3>How to plan your <?php echo $tax->name; ?> trip</h3>

        <p class="description"><?php echo get_field('how_to_plan_description' , $taxonomy . '_' . $term_id); ?></p>

        <?php 
            $plan_note = get_field('plan_note', $taxonomy . '_' . $term_id);
            $plan_info = get_field('plan_note_information', $taxonomy . '_' . $term_id);
        ?>

        <div class="row">
            <?php 
            if( have_rows('plan_steps', $taxonomy . '_' . $term_id) ):
                while( have_rows('plan_steps', $taxonomy . '_' . $term_id) ) : the_row(); 
                    if (get_row_index() == 1) { ?>
                        <div class="col-12">
                            <div class="plan-box first-box">
                                <h4 class="step">1</h4>
                                <h5><?php echo get_sub_field('plan_name'); ?></h5>
                                <?php echo get_sub_field('plan_description'); ?>
                            </div>
                        </div>
                    <?php 
                    }elseif (get_row_index() == 2) { ?>
                        <div class="col-12">
                            <div class="plan-box second-box">
                                <h4 class="step">2</h4>
                                <h5><?php echo get_sub_field('plan_name'); ?></h5>
                                <?php echo get_sub_field('plan_description'); ?>
                            </div>
                        </div>
                    <?php
                    }elseif (get_row_index() == 3) { ?>
                        <div class="col-12">
                            <div class="plan-box third-box">
                                <h4 class="step">3</h4>
                                <h5><?php echo get_sub_field('plan_name'); ?></h5>
                                <?php echo get_sub_field('plan_description'); ?>
                            </div>
                        </div>
                    <?php
                    }elseif (get_row_index() == 4) { ?>
                        <div class="col-12">
                            <div class="plan-box fourth-box">
                                <h4 class="step">4</h4>
                                <h5><?php echo get_sub_field('plan_name'); ?></h5>
                                <?php echo get_sub_field('plan_description'); ?>
                            </div>
                        </div>

                    <?php } ?>

        <?php   endwhile;
            endif;
        ?>
                        <div class="col-12">
                            <div class="info-plan">
                                <h4><?php echo $plan_note; ?></h4>
                                <p><?php echo $plan_info; ?></p>
                            </div>
                        </div>
        </div>
    </div>
</div>

<div class="footer-banner">
    <div class="container">
        <img src="<?php echo get_field('banner_image_footer', $taxonomy . '_' . $term_id); ?> ">
    </div>
</div>

<script>

	function getVals(){
  // Get slider values
  var parent = this.parentNode;
  var slides = parent.getElementsByTagName("input");
    var slide1 = parseFloat( slides[0].value );
    var slide2 = parseFloat( slides[1].value );
  // Neither slider will clip the other, so make sure we determine which is larger
  if( slide1 > slide2 ){ var tmp = slide2; slide2 = slide1; slide1 = tmp; }
  
  var displayElement = parent.getElementsByClassName("rangeValues")[0];
      displayElement.innerHTML = "£ " + slide1 + " - £" + slide2;
}

</script>
<script>
	function getValsd(){
  // Get slider values
  var parentd = this.parentNode;
  var slidesd = parentd.getElementsByTagName("input");
    var slide1d = parseFloat( slidesd[0].value );
    var slide2d = parseFloat( slidesd[1].value );
  // Neither slider will clip the other, so make sure we determine which is larger
  if( slide1d > slide2d ){ var tmpd = slide2d; slide2d = slide1d; slide1d = tmpd; }
  
  var displayElementd = parentd.getElementsByClassName("rangeValuesd")[0];
      displayElementd.innerHTML = slide1d + "h" + " - " + slide2d + "h";
}

window.onload = function(){
  // Initialize Sliders
  var sliderSectionsd = document.getElementsByClassName("range-sliderd");
      for( var xd = 0; xd < sliderSectionsd.length; xd++ ){
        var slidersd = sliderSectionsd[xd].getElementsByTagName("input");
        for( var yd = 0; yd < slidersd.length; yd++ ){
          if( slidersd[yd].type ==="range" ){
            slidersd[yd].oninput = getValsd;
            // Manually trigger event first time to display values
            slidersd[yd].oninput();
          }
        }
      }
  // Initialize Sliders
  var sliderSections = document.getElementsByClassName("range-slider");
      for( var x = 0; x < sliderSections.length; x++ ){
        var sliders = sliderSections[x].getElementsByTagName("input");
        for( var y = 0; y < sliders.length; y++ ){
          if( sliders[y].type ==="range" ){
            sliders[y].oninput = getVals;
            // Manually trigger event first time to display values
            sliders[y].oninput();
          }
        }
      }
}

$("#location").val( "<?php echo $tax->name; ?>" );
</script>
<script>
		    	/*$("input:checkbox").on('click', function() {
				  // in the handler, 'this' refers to the box clicked on
				  var $box = $(this);
				  if ($box.is(":checked")) {
				    // the name of the box is retrieved using the .attr() method
				    // as it is assumed and expected to be immutable
				    var group = "input:checkbox[name='" + $box.attr("name") + "']";
				    // the checked state of the group/box on the other hand will change
				    // and the current value is retrieved using .prop() method
				    $(group).prop("checked", false);
				    $box.prop("checked", true);
				  } else {
				    $box.prop("checked", false);
				  }
				});*/
		    </script>

<?php get_footer(); ?>