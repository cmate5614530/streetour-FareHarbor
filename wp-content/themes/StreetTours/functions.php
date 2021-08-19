<?php
// This function enqueues the Normalize.css for use. The first parameter is a name for the stylesheet, the second is the URL. Here we
// use an online version of the css file.
function add_normalize_CSS() {
    wp_enqueue_style( 'normalize-styles', "https://cdnjs.cloudflare.com/ajax/libs/normalize/7.0.0/normalize.min.css");
}
// Register a new sidebar simply named 'sidebar'
function add_widget_Support() {
                register_sidebar( array(
                                'name'          => 'Sidebar',
                                'id'            => 'sidebar',
                                'before_widget' => '<div>',
                                'after_widget'  => '</div>',
                                'before_title'  => '<h2>',
                                'after_title'   => '</h2>',
                ) );
}
// Hook the widget initiation and run our function
add_action( 'widgets_init', 'add_Widget_Support' );

// Register a new navigation menu
if ( ! function_exists( 'mytheme_register_nav_menu' ) ) {
 
    function mytheme_register_nav_menu(){
        register_nav_menus( array(
            'header-menu' => __( 'Header Menu', 'text_domain' ),
            'streetours-menu'  => __( 'Streetours Menu', 'text_domain' ),
            'features-menu'  => __( 'Features Menu', 'text_domain' ),
            'work-us-menu'  => __( 'Work with us Menu', 'text_domain' ),
            'help-menu'  => __( 'Help Menu', 'text_domain' ),
            'responsive-menu'  => __( 'Responsive Menu', 'text_domain' ),
        ) );
    }
    add_action( 'after_setup_theme', 'mytheme_register_nav_menu', 0 );
}


if( function_exists('acf_add_options_page') ) {
    
    acf_add_options_page();
    
}

add_theme_support( 'post-thumbnails' );



////Add new taxonomy to product
add_action( 'init', 'custom_taxonomy_service' );
function custom_taxonomy_service()  {
$labels = array(
    'name'                       => 'Services',
    'singular_name'              => 'Service',
    'menu_name'                  => 'Service',
    'all_items'                  => 'All Services',
    'parent_item'                => 'Parent Service',
    'parent_item_colon'          => 'Parent Service:',
    'new_item_name'              => 'New Service Name',
    'add_new_item'               => 'Add New Service',
    'edit_item'                  => 'Edit Service',
    'update_item'                => 'Update Service',
    'separate_items_with_commas' => 'Separate Service with commas',
    'search_items'               => 'Search Services',
    'add_or_remove_items'        => 'Add or remove Services',
    'choose_from_most_used'      => 'Choose from the most used Services',
);
$args = array(
    'labels'                     => $labels,
    'hierarchical'               => true,
    'public'                     => true,
    'show_ui'                    => true,
    'show_admin_column'          => true,
    'show_in_nav_menus'          => true,
    'show_tagcloud'              => true,
);
register_taxonomy( 'services', 'product', $args );
register_taxonomy_for_object_type( 'services', 'product' );
}


///////////////////////CPT spots
// Creating a Deals Custom Post Type
function custom_post_type_spots() {
	$labels = array(
		'name'                => __( 'Spots' ),
		'singular_name'       => __( 'Spot'),
		'menu_name'           => __( 'Spots'),
		'parent_item_colon'   => __( 'Parent Spot'),
		'all_items'           => __( 'All Spots'),
		'view_item'           => __( 'View Spot'),
		'add_new_item'        => __( 'Add New Spot'),
		'add_new'             => __( 'Add New'),
		'edit_item'           => __( 'Edit Spot'),
		'update_item'         => __( 'Update Spot'),
		'search_items'        => __( 'Search Spot'),
		'not_found'           => __( 'Not Found'),
		'not_found_in_trash'  => __( 'Not found in Trash')
	);
	$args = array(
		'label'               => __( 'spots'),
		'description'         => __( 'Best Spots'),
		'labels'              => $labels,
		'supports'            => array( 'title', 'editor', 'excerpt', 'author', 'thumbnail', 'revisions', 'custom-fields'),
		'public'              => true,
		'hierarchical'        => false,
		'show_ui'             => true,
		'show_in_menu'        => true,
		'show_in_nav_menus'   => true,
		'show_in_admin_bar'   => true,
		'has_archive'         => true,
		'can_export'          => true,
		'exclude_from_search' => false,
	        'yarpp_support'       => true,
		'taxonomies' 	      => array('post_tag'),
		'publicly_queryable'  => true,
		'capability_type'     => 'page'
);
	register_post_type( 'spots', $args );
}
add_action( 'init', 'custom_post_type_spots', 0 );



///////////////////////CPT things to do
// Creating a Deals Custom Post Type
function custom_post_type_things() {
	$labels = array(
		'name'                => __( 'Things to do' ),
		'singular_name'       => __( 'Things to do'),
		'menu_name'           => __( 'Things to do'),
		'parent_item_colon'   => __( 'Parent Things to do'),
		'all_items'           => __( 'All Things to do'),
		'view_item'           => __( 'View Things to do'),
		'add_new_item'        => __( 'Add New Things to do'),
		'add_new'             => __( 'Add New'),
		'edit_item'           => __( 'Edit Things to do'),
		'update_item'         => __( 'Update Things to do'),
		'search_items'        => __( 'Search Things to do'),
		'not_found'           => __( 'Not Found'),
		'not_found_in_trash'  => __( 'Not found in Trash')
	);
	$args = array(
		'label'               => __( 'things-to-do'),
		'description'         => __( 'Things to do'),
		'labels'              => $labels,
		'supports'            => array( 'title', 'editor', 'excerpt', 'author', 'thumbnail', 'revisions', 'custom-fields'),
		'public'              => true,
		'hierarchical'        => false,
		'show_ui'             => true,
		'show_in_menu'        => true,
		'show_in_nav_menus'   => true,
		'show_in_admin_bar'   => true,
		'has_archive'         => true,
		'can_export'          => true,
		'exclude_from_search' => false,
	        'yarpp_support'       => true,
		'taxonomies' 	      => array('post_tag'),
		'publicly_queryable'  => true,
		'capability_type'     => 'page'
);
	register_post_type( 'things-to-do', $args );
}
add_action( 'init', 'custom_post_type_things', 0 );




//same taxonomy for two CTP
add_action( 'init', 'build_taxonomies', 0 );
 function build_taxonomies() {
    register_taxonomy( 'locations', array('spots','things-to-do', 'product'), array( 'hierarchical' => true, 'label' => 'Location', 'query_var' => true, 'rewrite' => true ) );   
}

add_action( 'admin_head', function () { ?>
	<style>
		table.wp-list-table .column-name {
		    width: auto!important;
		}
	</style>
<?php
} );




//Enqueue the plugin's styles.
add_action( 'wp_enqueue_scripts', 'ci_comment_rating_styles' );
function ci_comment_rating_styles() {

	wp_register_style( 'ci-comment-rating-styles', plugins_url( '/', __FILE__ ) . 'assets/style.css' );

	wp_enqueue_style( 'dashicons' );
	wp_enqueue_style( 'ci-comment-rating-styles' );
}

//Create the rating interface.
add_action( 'comment_form_logged_in_after', 'ci_comment_rating_rating_field' );
add_action( 'comment_form_after_fields', 'ci_comment_rating_rating_field' );
function ci_comment_rating_rating_field () {
	?>
	<label for="rating">Rating<span class="required">*</span></label>
	<fieldset class="comments-rating">
		<span class="rating-container">
			<?php for ( $i = 5; $i >= 1; $i-- ) : ?>
				<input type="radio" id="rating-<?php echo esc_attr( $i ); ?>" name="rating" value="<?php echo esc_attr( $i ); ?>" /><label for="rating-<?php echo esc_attr( $i ); ?>"><?php echo esc_html( $i ); ?></label>
			<?php endfor; ?>
			<input type="radio" id="rating-0" class="star-cb-clear" name="rating" value="0" /><label for="rating-0">0</label>
		</span>
	</fieldset>
	<?php
}

//Save the rating submitted by the user.
add_action( 'comment_post', 'ci_comment_rating_save_comment_rating' );
function ci_comment_rating_save_comment_rating( $comment_id ) {
	if ( ( isset( $_POST['rating'] ) ) && ( '' !== $_POST['rating'] ) )
	$rating = intval( $_POST['rating'] );
	add_comment_meta( $comment_id, 'rating', $rating );
}

//Make the rating required.
add_filter( 'preprocess_comment', 'ci_comment_rating_require_rating' );
function ci_comment_rating_require_rating( $commentdata ) {
	if ( ! is_admin() && ( ! isset( $_POST['rating'] ) || 0 === intval( $_POST['rating'] ) ) )
	wp_die( __( 'Error: You did not add a rating. Hit the Back button on your Web browser and resubmit your comment with a rating.' ) );
	return $commentdata;
}

//Display the rating on a submitted comment.
add_filter( 'comment_text', 'ci_comment_rating_display_rating');
function ci_comment_rating_display_rating( $comment_text ){

	if ( $rating = get_comment_meta( get_comment_ID(), 'rating', true ) ) {
		$stars = '<p class="stars">';
		for ( $i = 1; $i <= $rating; $i++ ) {
			$stars .= '<span class="dashicons dashicons-star-filled"></span>';
		}
		$stars .= '</p>';
		$comment_text = $stars.$comment_text ;
		return $comment_text;
	} else {
		return $comment_text;
	}
}

//Get the average rating of a post.
function ci_comment_rating_get_average_ratings( $id ) {
	$comments = get_approved_comments( $id );

	if ( $comments ) {
		$i = 0;
		$total = 0;
		foreach( $comments as $comment ){
			$rate = get_comment_meta( $comment->comment_ID, 'rating', true );
			if( isset( $rate ) && '' !== $rate ) {
				$i++;
				$total += $rate;
			}
		}

		if ( 0 === $i ) {
			return false;
		} else {
			return round( $total / $i, 1 );
		}
	} else {
		return false;
	}
}

/*Display the average rating above the content.
add_filter( 'the_content', 'ci_comment_rating_display_average_rating' );
function ci_comment_rating_display_average_rating( $content ) {

	global $post;

	if ( false === ci_comment_rating_get_average_ratings( $post->ID ) ) {
		return $content;
	}

	$stars   = '';
	$average = ci_comment_rating_get_average_ratings( $post->ID );

	for ( $i = 1; $i <= $average + 1; $i++ ) {

		$width = intval( $i - $average > 0 ? 20 - ( ( $i - $average ) * 20 ) : 20 );

		if ( 0 === $width ) {
			continue;
		}

		$stars .= '<span style="overflow:hidden; width:' . $width . 'px" class="dashicons dashicons-star-filled"></span>';

		if ( $i - $average > 0 ) {
			$stars .= '<span style="overflow:hidden; position:relative; left:-' . $width .'px;" class="dashicons dashicons-star-empty"></span>';
		}
	}

	$custom_content  = '<p class="average-rating">This post\'s average rating is: ' . $average .' ' . $stars .'</p>';
	$custom_content .= $content;
	return $custom_content;
}
*/


function start_info(){

	$stars   = '';
	$average = ci_comment_rating_get_average_ratings( get_the_ID()  );

	for ( $i = 1; $i <= $average + 1; $i++ ) {
		
		$width = intval( $i - $average > 0 ? 20 - ( ( $i - $average ) * 20 ) : 20 );

		if ( 0 === $width ) {
			continue;
		}

		$stars .= '<span style="overflow:hidden; width:' . $width . 'px" class="dashicons dashicons-star-filled"></span>';

		if ( $i - $average > 0 ) {
			$stars .= '<span style="overflow:hidden; position:relative; left:-' . $width .'px;" class="dashicons dashicons-star-empty"></span>';
		}
	}

	if($average == '' ){
		$custom_content  = '5/5 <span style="overflow:hidden; width:20px" class="dashicons dashicons-star-filled"></span><span style="overflow:hidden; width:20px" class="dashicons dashicons-star-filled"></span><span style="overflow:hidden; width:20px" class="dashicons dashicons-star-filled"></span><span style="overflow:hidden; width:20px" class="dashicons dashicons-star-filled"></span><span style="overflow:hidden; width:20px" class="dashicons dashicons-star-filled"></span> ';
	}else{
		$custom_content  = '' . $average .'/5 ' . $stars .'';
	}
	
	
	return $custom_content;

}