<?php /* Template Name: All explore Page */ ?>
<?php get_header();  echo $_POST["type_service"]; ?>

<div class="all-explore">
	 <div class="container">
        <center><h3>Explore <?php echo $_GET['locations']; ?></h3></center>
        <br>
		<?php
                $args = array(
                    'post_type' => 'product',
                    'posts_per_page' => -1,
                    'tax_query' => array(
                        array(
                            'taxonomy' => 'locations',
                            'field'    => 'slug',
                            'terms'    => array( $_GET['locations'] ),
                            'operator' => 'IN'
                        ),
                    ),
                );
                $arr_posts = new WP_Query( $args );
                 
                if ( $arr_posts->have_posts() ) :
                 
                    while ( $arr_posts->have_posts() ) :
                        $arr_posts->the_post();
                        ?>
                            <?php echo get_template_part("components/experience","box"); ?>
                        <?php
                    endwhile;
                    wp_reset_postdata();
                endif;        
            ?>
     </div>
</div>	

<?php get_footer(); ?>