<?php /* Template Name: All Destinations Page */ ?>
<?php get_header(); ?>

<section id="all-destination-page">
<div class="container-foundry">
	<h2><?php echo get_the_title(); ?></h2>

	<br><br>
	<div class="boxes-popular">
			<?php
				$object = 'product';
				$output = 'objects';
				$taxonomies = get_object_taxonomies( $object, $output );
				$exclude = array( 'locations');

				if ( $taxonomies ) {

				    foreach ( $taxonomies  as $taxonomy ) {

				        if( !in_array( $taxonomy->name, $exclude ) ) {
				            continue;
				        }

				        $terms = get_terms( array(
				            'taxonomy' => $taxonomy->name,
				            'hide_empty' => 0,
				        ) );

				        if ( ! empty( $terms ) && ! is_wp_error( $terms ) ) {

				            $term_list = '<ul class="term-list">';

				            foreach ( $terms as $term ) {
				            	if (get_field('hero_image', 'locations_'.$term->term_id)) {
				            		$back = get_field('hero_image', 'locations_'.$term->term_id);
				            	}else{
				            		$back = 'http://streetours.com/wp-content/uploads/2021/04/Rectangle-503-2.png';
				            	}
				                $term_list .= '
				                <a href="' . esc_url( get_term_link( $term ) ) . '">
				                	<div class="box-popular">
										<div class="table-box" style="background-image: url('.$back.')!important;">
											<div class="table-cell">
												<h4>'.$term->name .'</h4>
											</div>
										</div>
									</div>
								</a>
				                ';         
				            }

				            $term_list .= '</ul>';

				            echo $term_list;
				        }

				    }

				}
				?>
		</div>

</div>
</section>
<?php get_footer(); ?>