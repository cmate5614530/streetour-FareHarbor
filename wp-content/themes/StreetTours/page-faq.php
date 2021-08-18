<?php /* Template Name: FAQ Page */ ?>
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

            <div class="faqs">
            	<?php
            		if( have_rows('faq') ):
					    while( have_rows('faq') ) : the_row(); ?>
							 <button class="accor"><?php echo get_sub_field('title'); ?></button>
							 <div class="cont">
				                <?php echo get_sub_field('content'); ?>
				             </div>
				              <h3 class="name-section"><?php echo get_sub_field('section_name'); ?></h3>
				<?php    
					    endwhile;
					endif;
            	?>
            </div>
            <h4 style="color: #2D3E50;">You haven't found an answer to your question? Don't hesitate to contact our team. </h4>

        </div>
        <div class="col-4 col-lg-4 col-md-4 col-sm-12">
          <div class="sidebar">
            
          </div>
        </div>
      </div>
  </div>
</div>
</section>

<script>
var acc = document.getElementsByClassName("accor");
var i;

for (i = 0; i < acc.length; i++) {
  acc[i].addEventListener("click", function() {
    this.classList.toggle("on");
    var panel = this.nextElementSibling;
    if (panel.style.display === "block") {
      panel.style.display = "none";
    } else {
      panel.style.display = "block";
    }
  });
}
</script>

<?php get_footer(); ?>