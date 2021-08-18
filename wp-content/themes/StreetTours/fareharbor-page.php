<?php /* Template Name: Fareharbor Page */ ?>
<?php get_header(); ?>
    <div class="main-banner">
<!--    <div class="table-box"  style="background-image: url('https://fh-sites.imgix.net/sites/4750/2018/06/09103433/Webp.net-resizeimage-e1625863058827.png?auto=compress%2Cformat&w=1600&fit=max');" >-->
    <div class="table-box" >
        <div class="table-cell" style="vertical-align: middle;height:19rem!important;">
            <div class="container-foundry">
                <h2><?php echo get_the_title(); ?></h2>
            </div>
        </div>
    </div>
    <div class="all-content">
        <?php
            echo apply_filters('the_content', $wp_query->post->post_content);
        ?>
    </div>

<?php get_footer(); ?>