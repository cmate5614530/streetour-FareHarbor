<?php /* Template Name: Filter Page */ ?>
<?php get_header(); ?>

<?php
	 $tax_id = $_GET['taxid'];
	 $cat_filter = $_GET['cat_filter']; 
	 $service = $_GET['type_service'];
	 $minprice = $_GET['min-price'];
	 $maxprice = $_GET['max-price'];
	 $minduration = $_GET['min-duration'];
	 $maxduration = $_GET['max-duration'];


	if (isset($_GET['search-text-desktop'])) {
		$searchtext = $_GET['search-text-desktop'];
	}elseif (isset($_GET['search-text-resp'])) {
		$searchtext = $_GET['search-text-resp'];
	}
	else{
		$searchtext = '';
	}

	//$terms = get_the_terms( 22, 'locations' );
	//print_r($terms);
	//$term = get_term( $taxid );
	
	//vARIABLES
	$location = $_GET['taxid'];
	if (!is_array($location)) {
		if ($location == '') {
			$location = array();
		}else{
			$location = array($location);
		}
	}

	$my_location = $_GET['my_location'];


	$services = $_GET['type_service'];
	$category = $_GET['cat_filter'];

	if (isset($_GET['min-price']) ) {
		$minPrice = $_GET['min-price'];
	}else{
		$minPrice = 0;
	}

	if (isset($_GET['max-price']) ) {
		$maxPrice = $_GET['max-price'];
	}else{
		$maxPrice = 1000;
	}

	
	if (isset($_GET['min-duration']) ) {
		$minTime = $_GET['min-duration'];
	}else{
		$minTime = 0;
	}

	if (isset($_GET['max-duration']) ) {
		$maxTime = $_GET['max-duration'];
	}else{
		$maxTime = 1000;
	}
	$maxTime = $maxTime+0.1;


	$order = $_GET['sort'];

	$paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;
	$args = array(
		'paged' => $paged,
		'posts_per_page'  => 5,
		'post_type'       => 'product',
		'tax_query' => array( 'relation' => 'AND' ),
		'meta_query'	=> array( 'relation'		=> 'AND',
			array(
				'key' => '_price',
		        'value' => array($minPrice, $maxPrice),
		        'compare' => 'BETWEEN',
		        'type' => 'NUMERIC'
			),
			array(
				'key'  => 'duration',
				'value'     => array( $minTime, $maxTime ),
				'compare'   => 'BETWEEN',
				'type'      => 'NUMERIC'
			)
		),
	);
	

	if(!empty($searchtext) ){

		$args['s'] = $searchtext;
	}

	if(!empty($location)){

		$args['tax_query'][] = array(
								'taxonomy' => 'locations',
								'field' => 'term_id',
								'terms' => $location,
		);
	}else{
		if(!empty($my_location)){
			//Buscar la taxonomia por titulo
			$my_term_location = $my_location;//el 1 deberia ser el id del term location
			//Si no existe no hace nada 
			if ($my_term_location != null) {
				$args['tax_query'][] = array(
								'taxonomy' => 'locations',
								'field' => 'slug',
								'terms' => $my_term_location,
				);
			}

		}
	}

	if(!empty($services) ){
		$args['tax_query'][] = array(
			'taxonomy' => 'services',
			'field' => 'term_id',
            'terms'    => $services 
		);

	}

	if(!empty($category) ){
		$args['tax_query'][] = array(
			'taxonomy' => 'product_cat',
			'field' => 'term_id',
            'terms'    => $category
		);

	}

	
	switch ($order) {
		case "Price: Low to High":
			
			$args['meta_key']	= '_price';
			$args['orderby']	= 'meta_value';
			$args['order']		= 'DESC';
			
			break;
		case "Price: High to Low":
			$args['meta_key']	= '_price';
			$args['orderby']	= 'meta_value';
			$args['order']		= 'ASC';
			break;

		default:
			$args['orderby']	= 'DATE';
			$args['order']		= 'ASC';
	}

?>
<form id="searchbytext" method="get" action="<?php echo get_the_permalink(368); ?>">
	<!--<input type="hidden" name="min-price" value="<?php echo $minPrice; ?>">
	<input type="hidden" name="max-price" value="<?php echo $maxPrice; ?>">

	<input type="hidden" name="min-duration" value="<?php echo $minTime; ?>">
	<input type="hidden" name="max-duration" value="<?php echo $maxTime; ?>">-->

<div class="activities">
	<div class="container">

		<div class="row">
		    <div class="col-lg-3 col-md-12 col-sm-12">
		    	<div class="pink-box">
		    		<h4>FREE<bR>CANCELLATION</h4>
		    		<p>Cancel up to 24 hours before your activity starts to get a refund.</p>
		    	</div>

		    	<div class="filter-bar">
		    		<center><h3 style="margin-bottom: 40px;">Filter</h3></center>

		    		<!--<script>
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
						    $('#your-loc').append('<input type="checkbox" id="btn-cat" name="taxid[]" value="" checked><label for="cat_filter"><span id="">'+ lc+'</span></label>');

						  }
						});
		    		</script>-->
		    		<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>

					<!--<div>Country: <span id="country"></span></div>
					  <div>State: <span id="state"></span></div>
					    <div>City: <span id="city"></span></div>
					      <div>Latitude: <span id="latitude"></span></div>
					        <div>Longitude: <span id="longitude"></span></div>
					          <div>IP: <span id="ip"></span></div>-->

		    		<h3>Locations</h3>
		    		<div class="all-checkbox">
						<?php 
				            $orderby = 'name';
					        $order = 'asc';
					        $hide_empty = false ;
					        $cat_args = array(
					            'orderby'    => $orderby,
					            'order'      => $order,
					            'hide_empty' => $hide_empty,
					        );
					         
					        $product_loc = get_terms( 'locations', $cat_args ); ?>
					        <div id="your-loc"></div>
					        <?php 
					        if( !empty($product_loc) ){ 
					            foreach ($product_loc as $key => $categoryloc) {  $back = get_field('icon',$categoryloc); 
					            	if (in_array($categoryloc->term_id, $location) || $my_location == $categoryloc->name) { ?>
					            		<input type="checkbox" class="div-loc" id="btn-cat" name="taxid[]" value="<?php echo $categoryloc->term_id; ?>" checked>
					            	<?php }else{ ?> 
					            		<input type="checkbox" class="div-loc" id="btn-cat" name="taxid[]" value="<?php echo $categoryloc->term_id; ?>">
					            	<?php } ?>
					            	
									<label for="cat_filter"><?php echo $categoryloc->name; ?></label>

					     <?php } 
					    } ?>

					</div>

		    		<h3>Categories</h3>
		    		<div class="all-checkbox">
		    			<?php
							$taxonomy = "product_cat";
							$args_terms_id_list = array(23,24,27,28,29);

							foreach ($args_terms_id_list as $current_term_id){ 
								$taxonomy_terms = get_term_by("id", $current_term_id , $taxonomy); 
								$back = get_field('icon',$taxonomy_terms); 
									if (in_array($taxonomy_terms->term_id, $category)){ ?>
										<input type="checkbox" id="btn-cat" name="cat_filter[]" value="<?php echo $taxonomy_terms->term_id; ?>" checked>
								<?php
									}else{ ?>
										<input type="checkbox" id="btn-cat" name="cat_filter[]" value="<?php echo $taxonomy_terms->term_id; ?>">
								<?php
									}
								?>
									
									<label for="cat_filter"><?php echo $taxonomy_terms->name; ?></label>


						<?php		 
							}
						?>

					</div>

		    		<h3>Services</h3>
		    		<div class="all-checkbox">
		    			<?php 
				            $orderby = 'name';
					        $order = 'asc';
					        $hide_empty = false ;
					        $cat_args = array(
					            'orderby'    => $orderby,
					            'order'      => $order,
					            'hide_empty' => $hide_empty,
					        );
					         
					        $product_categories = get_terms( 'services', $cat_args );
					         
					        if( !empty($product_categories) ){ 
					            foreach ($product_categories as $key => $categoryservice) {  $back = get_field('icon',$categoryservice); 
					            	if (in_array($categoryservice->term_id, $services)) { ?>
					            		<input type="checkbox" id="btn-ser" name="type_service[]" value="<?php echo $categoryservice->term_id; ?>" checked>
					            	<?php }else{ ?> 
					            		<input type="checkbox" id="btn-ser" name="type_service[]" value="<?php echo $categoryservice->term_id; ?>">
					            	<?php } ?>
					            	
									<label for="ser_filter"><?php echo $categoryservice->name; ?></label>

					     <?php } 
					    } ?>
		    		</div>

		    		<h3>Type of activity</h3>
		    		<div class="all-checkbox">
		    			<?php
							$taxonomy = "product_cat";
							$args_terms_id_list = array(30,31,23,32);

							foreach ($args_terms_id_list as $current_term_id){ 
								$taxonomy_terms = get_term_by("id", $current_term_id , $taxonomy); 
								$back = get_field('icon',$taxonomy_terms); 
									if (in_array($taxonomy_terms->term_id, $category)){ ?>
										<input type="checkbox" id="btn-cat" name="cat_filter[]" value="<?php echo $taxonomy_terms->term_id; ?>" checked>
								<?php
									}else{ ?>
										<input type="checkbox" id="btn-cat" name="cat_filter[]" value="<?php echo $taxonomy_terms->term_id; ?>">
								<?php
									}
								?>
									
									<label for="cat_filter"><?php echo $taxonomy_terms->name; ?></label>


						<?php		 
							}
						?>

					</div>

		    		<h3>Price</h3>
		    		<div class="all-checkbox">
		    				<div id="all-prices">
				    			<section class="range-slider">
								  <span class="rangeValues"></span>
								  <input value="<?php echo $minPrice; ?>" min="0" max="120" step="1" name="min-price" type="range">
								  <input style="position: relative;top: -44px;" value="<?php echo $maxPrice; ?>" min="0" max="120" step="1" name="max-price" type="range">
								</section>
				    		</div>

		    		</div>

		    		<h3>Duration</h3>
		    		<div class="all-checkbox">
		    			<div id="all-duration">
				    			<section class="range-sliderd">
								  <span class="rangeValuesd"></span>
								  <input value="<?php echo $minTime; ?>" min="1" max="120" step="1" name="min-duration" type="range">
								  <input style="position: relative;top: -44px;" value="<?php echo $maxTime; ?>" min="1" max="120" step="1" name="max-duration" type="range">
								</section>
		    			</div>

		    		</div>
		    		<input id="submit-all" type="button" style="border:none;" class="btn-blue filter-desktop" value="Filter">
		    		
				</div>
		    </div>
		    <div class="col-lg-9 col-md-12 col-sm-12">

		    	<div class="right-filters">
		    			<input type="text" name="search-text-desktop" placeholder="City or activity" <?php if ($searchtext) {
		    				echo 'value = "'.$searchtext.'" ';
		    			} ?> >
		    			<input type="submit" value="Search">
		    			<div>
			    			<div id="sort-btn" class="btn-blue">Sort by</div>
			    			<br>
			    			<div id="content-sort-btn">
				    		
				    					<label class="container">Recommended
										  <input type="radio" name="sort" value="Recommended" <?php  if( $_GET['sort'] == "Recommended" ){ echo 'checked'; } ?>>
										  <span class="checkmark"></span>
										</label>
									
										<label class="container">Price: Low to High
										  <input type="radio" name="sort" value="Price: Low to High" <?php  if( $_GET['sort'] == "Price: Low to High" ){ echo 'checked'; } ?>>
										  <span class="checkmark"></span>
										</label>
									
										<label class="container">Price: High to Low
										  <input type="radio" name="sort" value="Price: High to Low" <?php  if( $_GET['sort'] =="Price: High to Low" ){ echo 'checked'; } ?>>
										  <span class="checkmark"></span>
										</label>
				    			
			    			</div>
		    			</div>
		    			<script>
		    				$( "#sort-btn" ).click(function() {
		    					if ($( "#content-sort-btn" ).hasClass( "btn-active" )) {
		    						$( "#content-sort-btn" ).removeClass( "btn-active" );
		    					}else{
		    						$( "#content-sort-btn" ).addClass( "btn-active" );
		    					}
							});
		    			</script>

		    			
		    	</div>
	    	

		    	<div class="box-results">
		    				
		    				<?php
		    					$loop = new WP_Query( $args );
								if ( $loop->have_posts() ) {
								    while ( $loop->have_posts() ) : $loop->the_post(); ?>
								    		<?php 
						            		$product = wc_get_product(get_the_ID());
					                        $time = get_field('duration', get_the_ID()); ?>
					                        <div class="box-tour">

					                        	<div class="row">
												    <div class="col-4">
												    	<a href="<?php echo get_the_permalink(); ?>">
												    		<img class="img-box" src="<?php the_post_thumbnail_url(get_the_ID()); ?>"> 
												    	</a>
												    </div>
												    <div class="col-8">
												    		<div class="content-tour">
								                                 <div class="row">
								                                    <div class="col-7">
								                                    	<?php $term_obj_list = get_the_terms( get_the_ID(), 'product_cat' ); ?>
								                                    	<span class="type-tour"><?php echo $terms_string = join(', ', wp_list_pluck($term_obj_list, 'name')); ?></span>
								                                		<a href="<?php echo get_the_permalink(); ?>"><h5 style="text-align: left;"><?php echo get_the_title(); ?></h5></a>
								                                        
								                                        <span class="time"><img src="<?php echo get_template_directory_uri(); ?>/images/time.svg"><?php echo $time; ?> hours</span>
								                                    </div>
								                                    <div class="col-5">
								                                    	<h5 style="margin-bottom: 0!important;">4.7/5 
								                                            <img src="<?php echo get_template_directory_uri(); ?>/images/star-icon.svg">
								                                            <img src="<?php echo get_template_directory_uri(); ?>/images/star-icon.svg">
								                                            <img src="<?php echo get_template_directory_uri(); ?>/images/star-icon.svg">
								                                            <img src="<?php echo get_template_directory_uri(); ?>/images/star-icon.svg">
								                                            <img src="<?php echo get_template_directory_uri(); ?>/images/star-icon.svg">
								                                        </h5>
								                                    </div>
								                                  </div>
								                                  <p><?php echo get_the_excerpt(); ?></p>
								                                  <h2 style="text-align: right;">‎£ <?php echo $product->get_price(); ?></h2>
								                            </div>
												    </div>
												</div>

					                        </div>
								             

							<?php   endwhile;
									echo "<div class='pagination-results'>";
								    $total_pages = $loop->max_num_pages;

								    if ($total_pages > 1){

								        $current_page = max(1, get_query_var('paged'));

								        echo paginate_links(array(
								            'base' => preg_replace('/\?.*/', '/', get_pagenum_link(1)) . '%_%',
											'current' => max(1, get_query_var('paged')),
								            'format' => '/page/%#%',
								            'current' => $current_page,
								            'total' => $total_pages,
											
											// here you can pass custom query string to the pagination url
											'add_args' => array(
												'sort_by_type' => ( !empty($_GET['sort_by_type']) ) ? $_GET['sort_by_type'] : 'vote'
											),
								            'prev_text'    => __('«'),
								            'next_text'    => __('»'),
								        ));
								    }  
								    echo "</div>";  
								}
								wp_reset_postdata();

		    				?>

		    	</div>
		    </div>
		  </div>
	</div>
</div>
</form>

<form id="searchbytextresponsiveform" method="get" action="<?php echo get_the_permalink(368); ?>">
<!--Responsive result page-->
	<div class="activities activities-responsive">
	<div class="container">

		<input type="text" name="search-text-resp" placeholder="City or activity" <?php if ($searchtext) {
		    				echo 'value = "'.$searchtext.'" ';
		    			} ?> >
		<input type="submit" style="float: right;" value="Search">

		<ul class="accordion">
			<li>
				<button class="accordion-control">Locations</button>
				<div class="accordion-panel">
					<div class="all-checkbox">
						<?php 
				            $orderby = 'name';
					        $order = 'asc';
					        $hide_empty = false ;
					        $cat_args = array(
					            'orderby'    => $orderby,
					            'order'      => $order,
					            'hide_empty' => $hide_empty,
					        );
					         
					        $product_loc = get_terms( 'locations', $cat_args );
					         
					        if( !empty($product_loc) ){ 
					            foreach ($product_loc as $key => $categoryloc) {  $back = get_field('icon',$categoryloc); 
					            	if (in_array($categoryloc->term_id, $location)) { ?>
					            		<input type="checkbox" id="btn-cat" name="taxid[]" value="<?php echo $categoryloc->term_id; ?>" checked>
					            	<?php }else{ ?> 
					            		<input type="checkbox" id="btn-cat" name="taxid[]" value="<?php echo $categoryloc->term_id; ?>">
					            	<?php } ?>
					            	
									<label for="cat_filter"><?php echo $categoryloc->name; ?></label>

					     <?php } 
					    } ?>
					</div>
				</div>
			</li>
	  <li>
	  	<button class="accordion-control">Categories</button>
	  	<div class="accordion-panel">
	  			<div class="all-checkbox">
						<?php 
				            $orderby = 'name';
					        $order = 'asc';
					        $hide_empty = false ;
					        $cat_args = array(
					            'orderby'    => $orderby,
					            'order'      => $order,
					            'hide_empty' => $hide_empty,
					        );
					         
					        $product_categories = get_terms( 'product_cat', $cat_args );
					         
					        if( !empty($product_categories) ){ 
					            foreach ($product_categories as $key => $categoryvalue) {  $back = get_field('icon',$categoryvalue); 
					            	if (in_array($categoryvalue->term_id, $category)) { ?>
					            		<input type="checkbox" id="btn-cat" name="cat_filter[]" value="<?php echo $categoryvalue->term_id; ?>" checked>
					            	<?php }else{ ?> 
					            		<input type="checkbox" id="btn-cat" name="cat_filter[]" value="<?php echo $categoryvalue->term_id; ?>">
					            	<?php } ?>
					            	
									<label for="cat_filter"><?php echo $categoryvalue->name; ?></label>

					     <?php } 
					    } ?>
				</div>
	  	</div>
	  </li>
	  <li>
	  	<button class="accordion-control">Services</button>
	  	<div class="accordion-panel">
	  		<div class="all-checkbox">
		    			<?php 
				            $orderby = 'name';
					        $order = 'asc';
					        $hide_empty = false ;
					        $cat_args = array(
					            'orderby'    => $orderby,
					            'order'      => $order,
					            'hide_empty' => $hide_empty,
					        );
					         
					        $product_categories = get_terms( 'services', $cat_args );
					         
					        if( !empty($product_categories) ){ 
					            foreach ($product_categories as $key => $categoryservice) {  $back = get_field('icon',$categoryservice); 
					            	if (in_array($categoryservice->term_id, $services)) { ?>
					            		<input type="checkbox" id="btn-ser" name="type_service[]" value="<?php echo $categoryservice->term_id; ?>" checked>
					            	<?php }else{ ?> 
					            		<input type="checkbox" id="btn-ser" name="type_service[]" value="<?php echo $categoryservice->term_id; ?>">
					            	<?php } ?>
					            	
									<label for="ser_filter"><?php echo $categoryservice->name; ?></label>

					     <?php } 
					    } ?>
		    </div>
	  	</div>
	  </li>
	  <li>
	  	<button class="accordion-control">Price</button>
	  	<div class="accordion-panel">
	  		<div class="all-checkbox">
		    		<div id="all-prices">
				    	<section class="range-slider">
							<span class="rangeValues"></span>
							<input value="<?php echo $minPrice; ?>" min="0" max="120" step="1" name="min-price" type="range">
							<input style="position: relative;top: -44px;" value="<?php echo $maxPrice; ?>" min="0" max="120" step="1" name="max-price" type="range">
						</section>
				    </div>
		    </div>
	  	</div>
	  </li>
	  <li>
	  	<button class="accordion-control">Duration</button>
	  	<div class="accordion-panel">
	  		<div class="all-checkbox">
		    	<div id="all-duration">
				    <section class="range-sliderd">
						<span class="rangeValuesd"></span>
						<input value="<?php echo $minTime; ?>" min="1" max="120" step="1" name="min-duration" type="range">
						<input style="position: relative;top: -44px;" value="<?php echo $maxTime; ?>" min="1" max="120" step="1" name="max-duration" type="range">
					</section>
		    	</div>
		    </div>
	  	</div>
	  </li>
	</ul>

						 <div class="row">
						    <div class="col-6">
						    	<div id="sort-btnr" class="btn-blue">Sort by</div>
						    </div>
						    <div class="col-6">
						    	<input id="submit-all-responsive" type="button" style="border: none;" class="btn-blue filter" value="Filter">
						    </div>
						  </div>
						
						
			    			<br>
			    			<div id="content-sort-btnr">
				    		
				    					<label class="container">Recommended
										  <input type="radio" name="sort" value="Recommended" <?php  if( $_GET['sort'] == "Recommended" ){ echo 'checked'; } ?>>
										  <span class="checkmark"></span>
										</label>
									
										<label class="container">Price: Low to High
										  <input type="radio" name="sort" value="Price: Low to High" <?php  if( $_GET['sort'] == "Price: Low to High" ){ echo 'checked'; } ?>>
										  <span class="checkmark"></span>
										</label>
									
										<label class="container">Price: High to Low
										  <input type="radio" name="sort" value="Price: High to Low" <?php  if( $_GET['sort'] =="Price: High to Low" ){ echo 'checked'; } ?>>
										  <span class="checkmark"></span>
										</label>
				    			
			    			</div>
			    		<script>
		    				$( "#sort-btnr" ).click(function() {
		    					if ($( "#content-sort-btnr" ).hasClass( "btn-active" )) {
		    						$( "#content-sort-btnr" ).removeClass( "btn-active" );
		    					}else{
		    						$( "#content-sort-btnr" ).addClass( "btn-active" );
		    					}
							});
		    			</script>

		    			<div class="box-results">
		    				
		    				<?php
		    					$loop = new WP_Query( $args );
								if ( $loop->have_posts() ) {
								    while ( $loop->have_posts() ) : $loop->the_post(); ?>
								    		<?php 
						            		$product = wc_get_product(get_the_ID());
					                        $time = get_field('duration', get_the_ID()); ?>
					                        <div class="box-tour">

					                        	<div class="row">
												    <div class="col-4">
												    	<a href="<?php echo get_the_permalink(); ?>">
												    		<img class="img-box" src="<?php the_post_thumbnail_url(get_the_ID()); ?>"> 
												    	</a>
												    </div>
												    <div class="col-8">
												    		<div class="content-tour">
								                                 <div class="row">
								                                    <div class="col-7">
								                                    	<?php $term_obj_list = get_the_terms( get_the_ID(), 'product_cat' ); ?>
								                                    	<span class="type-tour"><?php echo $terms_string = join(', ', wp_list_pluck($term_obj_list, 'name')); ?></span>
								                                		<a href="<?php echo get_the_permalink(); ?>"><h5 style="text-align: left;"><?php echo get_the_title(); ?></h5></a>
								                                        
								                                        <span class="time"><img src="<?php echo get_template_directory_uri(); ?>/images/time.svg"><?php echo $time; ?> hours</span>
								                                    </div>
								                                    <div class="col-5">
								                                    	<h5 style="">4.7/5 
								                                            <img src="<?php echo get_template_directory_uri(); ?>/images/star-icon.svg">
								                                            <img src="<?php echo get_template_directory_uri(); ?>/images/star-icon.svg">
								                                            <img src="<?php echo get_template_directory_uri(); ?>/images/star-icon.svg">
								                                            <img src="<?php echo get_template_directory_uri(); ?>/images/star-icon.svg">
								                                            <img src="<?php echo get_template_directory_uri(); ?>/images/star-icon.svg">
								                                        </h5>
								                                    </div>
								                                  </div>
								                                  <p><?php echo get_the_excerpt(); ?></p>
								                                  <h2 style="text-align: right;">‎£ <?php echo $product->get_price(); ?></h2>
								                            </div>
												    </div>
												</div>

					                        </div>
								             

							<?php   endwhile;
									echo "<div class='pagination-results'>";
								    $total_pages = $loop->max_num_pages;

								    if ($total_pages > 1){

								        $current_page = max(1, get_query_var('paged'));

								        echo paginate_links(array(
								            'base' => preg_replace('/\?.*/', '/', get_pagenum_link(1)) . '%_%',
											'current' => max(1, get_query_var('paged')),
								            'format' => '/page/%#%',
								            'current' => $current_page,
								            'total' => $total_pages,
											
											// here you can pass custom query string to the pagination url
											'add_args' => array(
												'sort_by_type' => ( !empty($_GET['sort_by_type']) ) ? $_GET['sort_by_type'] : 'vote'
											),
								            'prev_text'    => __('«'),
								            'next_text'    => __('»'),
								        ));
								    }  
								    echo "</div>";  
								}
								wp_reset_postdata();

		    				?>

		    			</div>

		    			<br>
		    			<br>
		    			<div class="pink-box">
				    		<h4>FREE<bR>CANCELLATION</h4>
				    		<p>Cancel up to 24 hours before your activity starts to get a refund.</p>
				    	</div>

		</div>
	</div>
<!--End Responsive result page-->
</form>

<script>
	$('#submit-all').click(
		function(){
		 $("#searchbytext").submit();
	});
	$('#submit-all-responsive').click(
		function(){
		 $("#searchbytextresponsiveform").submit();
	});
</script>
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
</script>
<script>
  $(document).ready(function() {

  $('.accordion').on('click', '.accordion-control', function(e) {
	    e.preventDefault();
	    $(this).next('.accordion-panel').not(':animated').slideToggle();
	  });
	});
  </script>

  <script>
  	$( document ).ready(function() {
    	var text = $('.div-loc').next('label').text();
    	if (text == 'Soacha'){
    		alert('Content Soacha');
    	}
	});
   </script>


<?php get_footer(); ?>