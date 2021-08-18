<?php get_header(); ?>

<section id="sample-page">
<div class="container">
  <div class="header-location">
    <h3><?php echo get_the_title(); ?></h3>
  </div>

  <div class="all-content">
    <div class="row">
        <div class="col-8 col-lg-8 col-md-8 col-sm-12">
          <div class="grey-line"></div>
          <br>
            <?php
                echo apply_filters('the_content',$wp_query->post->post_content);
            ?>
            <?php if (get_the_ID() == 134) { ?>
              <script>
                $( ".wpcf7-form.init" ).prepend( '<div class="select-option"><p>Do you have a booking with Streetours?</p><a href="javascript:void(0)" class="btn-blue" id="yes">Yes</a><a href="javascript:void(0)" class="btn-blue" id="no">No</a></div>' );
              </script>
            <?php } ?>
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

<!--script to contact-->
<script>
 $(document).ready(function(){
        $(".select-option a").click(function(){
          var radioValue = $(this).attr('id');
            if(radioValue == 'yes'){
                $( ".form-1a" ).addClass( "form_act" );
                $( ".form-1b" ).removeClass( "form_act" );
            }else{
                $( ".form-1b" ).addClass( "form_act" );
                $( ".form-1a" ).removeClass( "form_act" );
            }
        });
    });
</script>
<!--end script contact-->
<?php get_footer(); ?>