<?php //get_header(); ?>
<!--<div class="main-banner">-->
<!--  <div class="table-box">-->
<!--    <div class="table-cell">-->
<!--      <div class="container-foundry">-->
<!--        <h2>Start planning your next<br>great experience</h2>-->
<!--        --><?php //get_search_form(); ?>
<!--        <p class="slogan"><img src="--><?php //echo get_template_directory_uri(); ?><!--/images/leaf.svg">We Support Sustainable Tourism</p>-->
<!--      </div>-->
<!---->
<!--      <div class="container-foundry" style="max-width: 1200px!important;">-->
<!--        <div class="items">-->
<!--	        <div class="row">-->
<!--			    <div class="col-4"><p><img src="--><?php //echo get_template_directory_uri(); ?><!--/images/Flag-outline.svg">Best Price Guarantee</p></div>-->
<!--			    <div class="col-4"><p><img src="--><?php //echo get_template_directory_uri(); ?><!--/images/heart-outline.svg">Verified Reviews</p></div>-->
<!--			    <div class="col-4"><p><img src="--><?php //echo get_template_directory_uri(); ?><!--/images/check-icon.svg">Free Cancellation</p></div>-->
<!--			</div>-->
<!--		</div>-->
<!--	  </div>-->
<!--      -->
<!--    </div>-->
<!--  </div>-->
<!--</div>-->
<!---->
<!--<div class="items responsive-items">-->
<!--	<div class="container-foundry">-->
<!--	<div class="row">-->
<!--		<div class="col-4"><p><img src="--><?php //echo get_template_directory_uri(); ?><!--/images/item-1.svg">Best Price<br>Guarantee</p></div>-->
<!--		<div class="col-4"><p><img src="--><?php //echo get_template_directory_uri(); ?><!--/images/item-2.svg">Verified<br>Reviews</p></div>-->
<!--		<div class="col-4"><p><img src="--><?php //echo get_template_directory_uri(); ?><!--/images/item-3.svg">Free<br>Cancellation</p></div>-->
<!--	</div>-->
<!--	</div>-->
<!--</div>-->
<!---->
<!--<div class="top-xp">-->
<!--	<div class="container">-->
<!--		<h3>Top Experiences in London</h3>-->
<!--		<div class="boxes-tour">-->
<!--			--><?php //echo do_shortcode('[ajax_load_more post_type="product" posts_per_page="6" taxonomy="locations" taxonomy_terms="london" taxonomy_operator="IN" button_label="View More"]'); ?>
<!--            <!----><?php///*
//                    $args = array(
//                    'post_type' => 'product',
//                    'posts_per_page' => 6,
//                );
//                $arr_posts = new WP_Query( $args );
//
//                if ( $arr_posts->have_posts() ) :
//
//                    while ( $arr_posts->have_posts() ) :
//                        $arr_posts->the_post();
//                        $product = wc_get_product( get_the_ID() );
//                        $time = wc_get_product_tag_list( $product->get_id(), ', ' );
//                        ?>
//                            <?php echo get_template_part("components/experience","box"); ?>
//                        <?php
//                    endwhile;
//                endif;*/
//            ?>
<!---->
<!--            <center><a class="white-btn" href="">View More</a></center>-->-->
<!---->
<!--        </div>-->
<!--	</div>-->
<!--</div>-->
<!---->
<!---->
<!--<div class="popular">-->
<!--	<div class="container">-->
<!--		<h3>Popular Destinations</h3>-->
<!---->
<!--		<div class="boxes-popular">-->
<!--			--><?php
//				$object = 'product';
//				$output = 'objects';
//				$taxonomies = get_object_taxonomies( $object, $output );
//				$exclude = array( 'locations');
//
//				if ( $taxonomies ) {
//
//				    foreach ( $taxonomies  as $taxonomy ) {
//
//				        if( !in_array( $taxonomy->name, $exclude ) ) {
//				            continue;
//				        }
//
//				        $terms = get_terms( array(
//				            'taxonomy' => $taxonomy->name,
//				            'hide_empty' => 0,
//				        ) );
//
//				        if ( ! empty( $terms ) && ! is_wp_error( $terms ) ) {
//
//				            $term_list = '<ul class="term-list">';
//
//				            foreach ( $terms as $term ) {
//				                $term_list .= '
//				                <a href="' . esc_url( get_term_link( $term ) ) . '">
//				                	<div class="box-popular">
//										<div class="table-box">
//											<div class="table-cell">
//												<h4>'.$term->name .'</h4>
//											</div>
//										</div>
//									</div>
//								</a>
//				                ';
//				            }
//
//				            $term_list .= '</ul>';
//
//				            echo $term_list;
//				        }
//
//				    }
//
//				}
//				?>
<!--		</div>-->
<!---->
<!--		<center><a style="margin-top: 10px;" class="white-btn" href="--><?php //echo get_the_permalink(155); ?><!--">View All Destinations</a></center>-->
<!--	</div>-->
<!--</div>-->
<!---->
<!--<div class="explore-london">-->
<!--    <div class="container">-->
<!--        <h3>Explore London</h3>-->
<!--        <div class="boxes-explore">-->
<!--            <div class="row">-->
<!--                        --><?php //
//                                $args = array(
//                                'post_type' => 'product',
//                                'posts_per_page' => 8,
//                                'tax_query' => array(
//                                    array (
//                                        'taxonomy' => 'locations',
//                                        'field' => 'slug',
//                                        'terms' => 'london',
//                                    )
//                                ),
//                            );
//                            $arr_posts = new WP_Query( $args );
//
//                            if ( $arr_posts->have_posts() ) :
//
//                                while ( $arr_posts->have_posts() ) :
//                                    $arr_posts->the_post();
//                                    ?>
<!--                                        --><?php //echo get_template_part("components/explore","box"); ?>
<!--                                    --><?php
//                                endwhile;
//                            endif;
//                            ?>
<!--            </div>-->
<!---->
<!--        </div>-->
<!--        <center><a class="white-btn" href="">View More</a></center>-->
<!--    </div>-->
<!--</div>-->
<!---->
<?php //echo get_template_part("components/offers","box"); ?>
<?php //echo get_template_part("components/offers","boxresponsive"); ?>
<!---->
<!--<div class="top-destinations">-->
<!--	<div class="container">-->
<!--		<h3>Top Destinations</h3>-->
<!---->
<!--			<div class="slide-destinations">-->
<!--			  <div>-->
<!--			  			<div class="table-box">-->
<!--				    		<div class="table-cell">-->
<!--				    			<h3>EDINBURGH</h3>-->
<!--				    		</div>-->
<!--				    	</div>-->
<!--			  </div>-->
<!--			  <div>-->
<!--			  			<div class="table-box">-->
<!--				    		<div class="table-cell">-->
<!--				    			<h3>EDINBURGH</h3>-->
<!--				    		</div>-->
<!--				    	</div>-->
<!--			  </div>-->
<!--			  <div>-->
<!--			  			<div class="table-box">-->
<!--				    		<div class="table-cell">-->
<!--				    			<h3>EDINBURGH</h3>-->
<!--				    		</div>-->
<!--				    	</div>-->
<!--			  </div>-->
<!--			  <div>-->
<!--			  			<div class="table-box">-->
<!--				    		<div class="table-cell">-->
<!--				    			<h3>EDINBURGH</h3>-->
<!--				    		</div>-->
<!--				    	</div>-->
<!--			  </div>-->
<!--			  <div>-->
<!--			  			<div class="table-box">-->
<!--				    		<div class="table-cell">-->
<!--				    			<h3>EDINBURGH</h3>-->
<!--				    		</div>-->
<!--				    	</div>-->
<!--			  </div>-->
<!--			  <div>-->
<!--			  			<div class="table-box">-->
<!--				    		<div class="table-cell">-->
<!--				    			<h3>EDINBURGH</h3>-->
<!--				    		</div>-->
<!--				    	</div>-->
<!--			  </div>-->
<!--			  <div>-->
<!--			  			<div class="table-box">-->
<!--				    		<div class="table-cell">-->
<!--				    			<h3>EDINBURGH</h3>-->
<!--				    		</div>-->
<!--				    	</div>-->
<!--			  </div>-->
<!--			  <div>-->
<!--			  			<div class="table-box">-->
<!--				    		<div class="table-cell">-->
<!--				    			<h3>EDINBURGH</h3>-->
<!--				    		</div>-->
<!--				    	</div>-->
<!--			  </div>-->
<!--			</div>-->
<!--		<!--<div>-->
<!--			 <div class="row">-->
<!---->
<!--			    <div class="col-lg-3 col-md-3 col-sm-12">-->
<!--			    	<div class="table-box">-->
<!--			    		<div class="table-cell">-->
<!--			    			<h3>EDINBURGH</h3>-->
<!--			    		</div>-->
<!--			    	</div>-->
<!--			    </div>-->
<!--			    -->
<!--			 </div>-->
<!--		</div>-->-->
<!--	</div>-->
<!--</div>-->
<!---->
<!---->
<!--<script>-->
<!--	$( document ).ready(function() {-->
<!--    	$(".searchform #s").attr("placeholder", "City or activity");-->
<!--	});-->
<!--</script>-->
<?php //get_footer(); ?>