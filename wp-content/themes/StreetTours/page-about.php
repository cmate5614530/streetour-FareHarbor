<?php /* Template Name: About Page */ ?>
<?php get_header(); ?>

<div class="about">
<div class="location-banner">
    <div class="table-box" style="background-image: url(<?php echo get_the_post_thumbnail_url( $post_id, 'full' ); ?>) !important;">
        <div class="table-cell">
            <h1><?php echo get_the_title(); ?></h1>
        </div>
    </div>
</div>

<div class="two-columns">
	<div class="container">
		
			<?php 
				if( have_rows('mission') ):
				    while( have_rows('mission') ) : the_row(); ?>
				    	<?php 
				    		$number = get_row_index();
				    		if($number % 2 == 0){ ?>
						        <div class="row">
							    	<div class="col-6 col-lg-6 col-md-6 col-sm-12">
							    		<div class="content-img">
							    			<img src="<?php echo get_sub_field('image'); ?>">
							    		</div>
							    	</div>
							    	<div class="col-6 col-lg-6 col-md-6 col-sm-12">
							    		<div class="content-info">
							    			<?php echo get_sub_field('content'); ?>
							    		</div>
							    	</div>
						       </div>
						<?php }else{ ?>
							<div class="row">
							    	<div class="col-6 col-lg-6 col-md-6 col-sm-12">
							    		<div class="content-info">
							    			<?php echo get_sub_field('content'); ?>
							    		</div>
							    	</div>
							    	<div class="col-6 col-lg-6 col-md-6 col-sm-12">
							    		<div class="content-img">
							    			<img src="<?php echo get_sub_field('image'); ?>">
							    		</div>
							    	</div>
						       </div>
						<?php } ?>

			<?php	endwhile;
				endif;
			?>
		  
	</div>
</div>

<!--responsive two columns-->
<div class="two-columns two-col-responsive">
	<div class="container">
		
			<?php 
				if( have_rows('mission') ):
				    while( have_rows('mission') ) : the_row(); ?>
							<div class="row">
							    	<div class="col-6 col-lg-6 col-md-6 col-sm-12">
							    		<div class="content-info">
							    			<?php echo get_sub_field('content'); ?>
							    		</div>
							    	</div>
							    	<div class="col-6 col-lg-6 col-md-6 col-sm-12">
							    		<div class="content-img">
							    			<img src="<?php echo get_sub_field('image'); ?>">
							    		</div>
							    	</div>
						       </div>

			<?php	endwhile;
				endif;
			?>
		  
	</div>
</div>
<!--end responsive columns-->

<div class="what-we-do">
	<div class="container">
		<?php echo get_field('what_we_do'); ?>
	</div>
</div>

<div class="two-columns-we-do">
	<div class="container">
		
		<?php 
				if( have_rows('what_we_do_repeater') ):
				    while( have_rows('what_we_do_repeater') ) : the_row(); ?>
				    	<?php 
				    		$number = get_row_index();
				    		if($number % 2 == 0){ ?>
						        <div class="row">
							    	<div class="col-6 col-lg-6 col-md-6 col-sm-12">
							    		<div class="content-img">
							    			<img src="<?php echo get_sub_field('image'); ?>">
							    		</div>
							    	</div>
							    	<div class="col-6 col-lg-6 col-md-6 col-sm-12">
							    		<div class="content-info">
							    			<span class="number"><?php echo $number; ?></span>
							    			<?php echo get_sub_field('information'); ?>
							    		</div>
							    	</div>
						       </div>
						<?php }else{ ?>
							<div class="row">
							    	<div class="col-6 col-lg-6 col-md-6 col-sm-12">
							    		<div class="content-info">
							    			<span class="number"><?php echo $number; ?></span>
							    			<?php echo get_sub_field('information'); ?>
							    		</div>
							    	</div>
							    	<div class="col-6 col-lg-6 col-md-6 col-sm-12">
							    		<div class="content-img">
							    			<img src="<?php echo get_sub_field('image'); ?>">
							    		</div>
							    	</div>
						       </div>
						<?php } ?>

			<?php	endwhile;
				endif;
			?>

	</div>
</div>


<div class="two-columns-we-do responsive-two-columns-we-do">	
		<?php 
				if( have_rows('what_we_do_repeater') ):
				    while( have_rows('what_we_do_repeater') ) : the_row(); ?>
				    	<?php 
				    		$number = get_row_index(); ?>
				    			<div class="row" style="margin-top: -50px;">
				    				<div class="col-6 col-lg-6 col-md-6 col-sm-12 par">
							    		<div class="content-info">
							    			<span class="number"><?php echo $number; ?></span>
							    			<?php echo get_sub_field('information'); ?>
							    		</div>
							    	</div>
							    	<div class="col-6 col-lg-6 col-md-6 col-sm-6 par text-info">
							    		<?php echo get_sub_field('information'); ?>
							    	</div>
							    	<div class="col-6 col-lg-6 col-md-6 col-sm-6 par">
							    		<div class="content-img">
							    			<img src="<?php echo get_sub_field('image'); ?>">
							    		</div>
							    	</div>
						       </div>
						       <img class="par-line" src="<?php echo get_template_directory_uri(); ?>/images/largeline-blue.svg">

			<?php	endwhile;
				endif;
			?>
</div>

<div class="embed-container">
	<div class="container"><?php the_field('video'); ?></div>
</div>

<?php if( have_rows('partners') ){ ?>
<div class="partners">
	<div class="container">
		<h3>Partners</h3>
		<?php
			if( have_rows('partners') ):
			    while( have_rows('partners') ) : the_row(); ?>
			    	<img src="<?php echo get_sub_field('partner_logo'); ?>">
	<?php	    endwhile;
			endif;
		?>
	</div>
</div>
<?php } ?>

<?php if( have_rows('we_have_appeared_on') ){ ?>
<div class="appeared">
	<div class="container">
		<h3>We have appeared on</h3>
		<?php
			if( have_rows('we_have_appeared_on') ):
			    while( have_rows('we_have_appeared_on') ) : the_row(); ?>
			    	<img src="<?php echo get_sub_field('logo'); ?>">
	<?php	    endwhile;
			endif;
		?>
	</div>
</div>
<?php } ?>

</div><!--End about div-->
<?php get_footer(); ?>