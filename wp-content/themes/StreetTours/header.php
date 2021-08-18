<!DOCTYPE html>
<html <?php language_attributes(); ?>
 <head>
   <title><?php bloginfo('name'); ?> &raquo; <?php is_front_page() ? bloginfo('description') : wp_title(''); ?></title>
   <meta charset="<?php bloginfo( 'charset' ); ?>">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <link rel="stylesheet" href="<?php bloginfo('stylesheet_url'); ?>">
   <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">

   <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
	<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
	<script src="https://code.jquery.com/jquery-3.4.1.js"></script>

	<link rel="stylesheet" type="text/css" href="<?php echo get_template_directory_uri(); ?>/slick/slick/slick.css"/>
	<link rel="stylesheet" type="text/css" href="<?php echo get_template_directory_uri(); ?>/slick/slick/slick-theme.css"/>

	<link rel="preconnect" href="https://fonts.gstatic.com">
	<link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;700&display=swap" rel="stylesheet">
	<link href="https://fonts.googleapis.com/css2?family=Roboto&display=swap" rel="stylesheet">

	<!-- Hotjar Tracking Code for www.streetours.com -->
	<script>
		(function(h,o,t,j,a,r){
		h.hj=h.hj||function(){(h.hj.q=h.hj.q||[]).push(arguments)};
		h._hjSettings={hjid:2211784,hjsv:6};
		a=o.getElementsByTagName('head')[0];
		r=o.createElement('script');r.async=1;
		r.src=t+h._hjSettings.hjid+j+h._hjSettings.hjsv;
		a.appendChild(r);
		})(window,document,'https://static.hotjar.com/c/hotjar-','.js?sv=');
	</script>
    <!-- Global site tag (gtag.js) - Google Analytics -->
<script async src="https://www.googletagmanager.com/gtag/js?id=G-P947BTR1XY"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'G-P947BTR1XY');
</script>

<!-- Facebook Pixel Code -->
<script>
!function(f,b,e,v,n,t,s)
{if(f.fbq)return;n=f.fbq=function(){n.callMethod?
n.callMethod.apply(n,arguments):n.queue.push(arguments)};
if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
n.queue=[];t=b.createElement(e);t.async=!0;
t.src=v;s=b.getElementsByTagName(e)[0];
s.parentNode.insertBefore(t,s)}(window, document,'script',
'https://connect.facebook.net/en_US/fbevents.js');
fbq('init', '1000695624082188');
fbq('track', 'PageView');
</script>
<noscript><img height="1" width="1" style="display:none"
src="https://www.facebook.com/tr?id=1000695624082188&ev=PageView&noscript=1"
/></noscript>
<!-- End Facebook Pixel Code -->

   <?php wp_head(); ?>
 </head>
 <body <?php body_class(); ?>>

    <header>
		   <div class="row">
		    <div class="col col-lg-1 col-md-1 col-sm-1 col-xs-1">&nbsp;</div>
		    <div class="col col-lg-4 col-md-4 col-sm-4 col-xs-4"><a href="<?php echo home_url(); ?>/"><img style="position: relative;top: 7px;" src="<?php echo get_template_directory_uri(); ?>/images/Streetours-vector.svg"></a></div>
		    <div class="col col-lg-7 col-md-7 col-sm-7 col-xs-7">
		    	<div class="right-section">
		    		<div class="search-header">
		    			<form method="get" id="search-form" action="<?php echo get_the_permalink(368); ?>">
				        	<input type="text" name="search-text-desktop" placeholder="City or activity" <?php if ($_GET['search-text-desktop']) {
		    				echo 'value = "'.$_GET['search-text-desktop'].'" ';
		    			} ?> >

		    				<script>
				    			$.ajax({
								  url: "https://geolocation-db.com/jsonp",
								  jsonpCallback: "callback",
								  dataType: "jsonp",
								  success: function(location) {
								    //$('#country').html(location.country_name);
								    //$('#state').html(location.state);
								    $('#city').html(location.city);
								    //$('#latitude').html(location.latitude);
								    //$('#longitude').html(location.longitude);
								    //$('#ip').html(location.IPv4);
								    var lc = location.city;
								    $('#input-loc').append('<input type="hidden" name="my_location" value="'+lc+'">');
								  }
								});
				    		</script>

				        	<input type="submit" value="">
				        	<div id="input-loc"></div>
				        </form>
		    		</div>
		    		<script>
		    			$( "#btn-search-hd" ).click(function() {
						  if ($( ".search-header" ).hasClass( "actv" )) {
						  	$( ".search-header" ).removeClass( "actv" );
						  }else{
						  	$( ".search-header" ).addClass( "actv" );
						  }
						});
		    		</script>
		    		<!--<a href="">English <svg width="17" height="10" viewBox="0 0 17 10" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M8.44642 9.90788L16.176 2.17827L14.3587 0.358398L8.44642 6.27458L2.53538 0.358398L0.716797 2.17698L8.44642 9.90788Z" fill="white"/></svg></a>
		    		<a href="">GBP (£)</a>-->
		    		<a style="margin: 0;" href="<?php echo get_the_permalink(501); ?>"><img src="<?php echo get_template_directory_uri(); ?>/images/question.svg"></a>
		    		<a class="login" href="<?php echo get_permalink(69); ?>"><img src="<?php echo get_template_directory_uri(); ?>/images/icon-user.svg"></a>
		    	</div>
		    </div>
		  </div>
 	</header>

 	<header id="responsive-header">
 		<div class="row">
 			<div class="col-4">
 				<div id="btn-hamburger"></div>
 			</div>
 			<div class="col-4">
 				<a href="<?php echo home_url(); ?>/"><img src="<?php echo get_template_directory_uri(); ?>/images/Streetours-vector.svg"></a>
 			</div>
 			<div class="col-4 right-section">
 				<!--<a style="margin: 0;" href="<?php// echo get_the_permalink(289); ?>">
 					<img class="q-img" style="margin-right: 30px;" src="<?php// echo get_template_directory_uri(); ?>/images/question.svg">
 				</a>-->
 				<a href="<?php echo get_permalink(69); ?>"><img src="<?php echo get_template_directory_uri(); ?>/images/icon-user.svg"></a>
 			</div>
 		</div>
 		<div class="content-menu">
 					<div class="header-responsive-menu" style="display: block;width: 100%;">
 						<center><a href="<?php echo home_url(); ?>/"><img style="position: relative;" src="<?php echo get_template_directory_uri(); ?>/images/Streetours-vector.svg"></a></center>
 						<div style="float: right;" id="btn-hamburger-close"></div>
 					</div>
 					<div class="search-header">
		    			<form action="<?php echo get_the_permalink(368); ?>" id="searchform" method="get">
						    <input type="text" name="search-text-resp" id="" placeholder="Search destination or experience" <?php if ($_GET['search-text-resp']) {
		    				echo 'value = "'.$_GET['search-text-resp'].'" ';
		    			} ?> />

		    				<script>
				    			$.ajax({
								  url: "https://geolocation-db.com/jsonp",
								  jsonpCallback: "callback",
								  dataType: "jsonp",
								  success: function(location) {
								    //$('#country').html(location.country_name);
								    //$('#state').html(location.state);
								    $('#city').html(location.city);
								    //$('#latitude').html(location.latitude);
								    //$('#longitude').html(location.longitude);
								    //$('#ip').html(location.IPv4);
								    var lc = location.city;
								    $('#input-locr').append('<input type="hidden" name="my_location" value="'+lc+'">');
								  }
								});
				    		</script>

						    <input type="submit" value=" " />

						    <div id="input-locr"></div>
						</form>
						<?php wp_nav_menu( array( 'theme_location' => 'responsive-menu', 'container_class' => 'custom-menu-class' ) ); ?>
		    		</div>
		    		<!--<a href="">English <svg width="17" height="10" viewBox="0 0 17 10" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M8.44642 9.90788L16.176 2.17827L14.3587 0.358398L8.44642 6.27458L2.53538 0.358398L0.716797 2.17698L8.44642 9.90788Z" fill="white"/></svg></a>
		    		<a href="">GBP (£)</a>-->
 		</div>
 	</header>