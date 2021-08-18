<?php

do_action( 'turitop_booking_system_service_grid_before', $data, $service_id, $this->tbs_data, $atts );

?>

<div class="turitop_booking_system_service_grid <?php echo $service_classes; ?>">

  <?php do_action( 'turitop_booking_system_service_title_before', $data, $service_id, $this->tbs_data, $atts ); ?>

  <div class="turitop_booking_system_service_title <?php echo $service_title_classes; ?>">

    <?php if ( $this->tbs_data[ 'content_service' ] == 'whole_content' ){ ?>

      <?php echo $title; ?>

    <?php }else{ ?>

      <a href="<?php echo $page_url; ?>" <?php echo $target_blank; ?>><?php echo $title; ?></a>

    <?php } ?>

  </div>

  <?php do_action( 'turitop_booking_system_service_image_before', $data, $service_id, $this->tbs_data, $atts ); ?>

  <div class="turitop_booking_system_service_image <?php echo $service_image_classes; ?>">

    <?php if ( $this->tbs_data[ 'content_service' ] == 'whole_content' ){ ?>

      <img src="<?php echo $image; ?>" alt="<?php echo $title; ?>">

    <?php }else{ ?>

      <a href="<?php echo $page_url; ?>" <?php echo $target_blank; ?>><img src="<?php echo $image; ?>" alt="<?php echo $title; ?>"></a>

    <?php } ?>

  </div>

  <?php if ( apply_filters( 'turitop_booking_system_display_service_summary', $this->tbs_data[ 'content_service' ] == 'whole_content', $data, $service_id, $this->tbs_data, $atts ) ): ?>

    <?php do_action( 'turitop_booking_system_service_summary_before', $data, $service_id, $this->tbs_data, $atts ); ?>

    <div class="turitop_booking_system_service_summary <?php echo $service_summary_classes; ?>">

        <?php echo $summary; ?>

    </div>

  <?php endif; ?>

  <?php do_action( 'turitop_booking_system_service_box_button_before', $data, $service_id, $this->tbs_data, $atts ); ?>

  <div class="turitop_bswp_button_box_wrap turitop_booking_system_service_box_button <?php echo $service_box_button_classes; ?>">

          <?php echo $button; ?>

  </div>

  <?php do_action( 'turitop_booking_system_service_description_before', $data, $service_id, $this->tbs_data, $atts ); ?>

  <?php if ( apply_filters( 'turitop_booking_system_display_service_description', $this->tbs_data[ 'content_service' ] == 'whole_content', $data, $service_id, $this->tbs_data, $atts ) ): ?>

    <div class="turitop_booking_system_service_description <?php echo $service_description_classes; ?>">

        <?php echo $description; ?>

    </div>

  <?php endif; ?>

</div>

<?php

do_action( 'turitop_booking_system_service_grid_after', $data, $service_id, $this->tbs_data, $atts );
