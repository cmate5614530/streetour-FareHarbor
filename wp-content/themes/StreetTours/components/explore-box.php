<?php 
                                    $product = wc_get_product( get_the_ID() );
                                    $time = wc_get_product_tag_list( $product->get_id(), ', ' );
                                    $term_obj_list = get_the_terms( get_the_ID(), 'product_cat' );
                                    ?>
                                                 <div class="col-lg-3 col-md-3 col-sm-6 col-xs-6">
                                                <div class="box-explore">
                                                    <a href="<?php echo get_the_permalink(); ?>">
                                                    <img style="width: 100%;height: 116px;object-fit: cover;" src="<?php the_post_thumbnail_url(get_the_ID());?>">
                                                    </a>
                                                    <div class="content-explore">
                                                        <span class="type-tour"><?php echo $terms_string = join(', ', wp_list_pluck($term_obj_list, 'name')); ?></span>
                                                        <span class="time-tour"><?php echo $time; ?></span>
                                                        <a href="<?php echo get_the_permalink(); ?>"><h4><?php the_title(); ?></h4></a>
                                                        <div class="row">
                                                            <div class="col-8">
                                                                <h5 style="margin-bottom: 0!important;"><?php echo start_info(); ?> </h5>
                                                            </div>
                                                            <div class="col-4">
                                                                <h2>‎£ <?php echo $product->get_price(); ?></h2>
                                                            </div>
                                                          </div>
                                                    </div>
                                                </div>
                                            </div>