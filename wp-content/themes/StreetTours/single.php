<?php get_header(); ?>
<section id="single-page">
<div class="gallery-responsive">
  <?php 
    if( have_rows('gallery') ):
                while( have_rows('gallery') ) : the_row(); ?>
                  <div><img src="<?php echo get_sub_field('image'); ?> "></div>
    <?php     endwhile;
        endif;
  ?>
</div>
  
<div class="collage only">
  <div class="container">
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
                          <img src="<?php echo $img1; ?> ">
                          <img src="<?php echo $img2; ?> ">
                        </div>
              <?php }else{ ?>
                        <div class="col-lg-3 col-md-3 col-sm-3">
                          <img src="<?php echo get_sub_field('image'); ?> ">
                        </div>
                    <?php } ?>
        <?php   endwhile;
            else: ?>
              <img class="img-complete" src="<?php echo get_the_post_thumbnail_url( $post_id, 'full' ); ?>">
      <?php endif;  
        ?>
    </div>
  </div>
</div>

<div class="container">
  <div class="header-location">
    <h3><?php echo get_the_title(); ?></h3>
  </div>

  <div class="all-content" style="padding-bottom: 60px;">
    <div class="row">
        <div class="col-8 col-lg-8 col-md-8 col-sm-12">
          <div class="grey-line"></div>
          <br>
            <p><?php echo get_the_content(); ?></p>
          <div class="grey-line"></div>
        </div>
        <div class="col-4 col-lg-4 col-md-4 col-sm-12">
          <div class="sidebar">
            
          </div>
        </div>
      </div>

        
  </div>
</div>
</section>
<?php get_footer(); ?>