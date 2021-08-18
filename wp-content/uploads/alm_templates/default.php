<?php $product = wc_get_product( get_the_ID() );
                        $time = get_field('duration'); ?>
                        <div class="box-tour">
                        <a href="<?php echo get_the_permalink(); ?>">
                            <div class="table-box" style="background-image: url('<?php the_post_thumbnail_url(get_the_ID());?>');">
                                <div class="table-cell">
                                    <?php $term_obj_list = get_the_terms( get_the_ID(), 'product_cat' ); ?>
                                    <span><?php echo get_field('icon', $term_obj_list[0]);  ?><?php echo $terms_string = join(', ', wp_list_pluck($term_obj_list, 'name')); ?></span>
                                </div>
                        </div></a>
                            <div class="content-tour">
                                <a href="<?php echo get_the_permalink(); ?>"><h5><?php the_title(); ?></h5></a>
                                 <div class="row">
                                    <div class="col-7">
                                         <h5 style="margin-bottom: 0!important;"><?php echo start_info(); ?> </h5>
                                        <span class="time"><img src="<?php echo get_template_directory_uri(); ?>/images/time.svg"><?php echo $time; ?> hours</span>
                                    </div>
                                    <div class="col-5">
                                        <h2>‎£ <?php echo $product->get_price(); ?></h2>
                                    </div>
                                  </div>
                                </div>
                            </div>