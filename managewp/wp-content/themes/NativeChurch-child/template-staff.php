<?php
/*
  Template Name: Staff
 */
get_header();
$variable_post_id =  get_the_ID();
$number= get_post_meta(get_the_ID(),'imic_staff_to_show_on',true);
$order= get_post_meta(get_the_ID(),'imic_staff_select_orderby',true);
?>
    <div class="container">
        <div class="row">
        <?php 
                while(have_posts()):the_post();
                    ob_start();
                    the_content();
                    $content = ob_get_clean();	
                endwhile; ?>
            <div class="col-md-<?php echo imic_woocommerce_full_width_class($variable_post_id,9); ?>">  
		<?php if($order=="ID") {$sort_order = "DESC"; } else { $sort_order = "ASC"; }
        $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
                query_posts(array(
            'post_type' => 'staff',
            'posts_per_page' => $number,
			'orderby' => $order,
			'order' => $sort_order,
			'paged' => $paged
        ));
        if (have_posts()):
            while (have_posts()):the_post();
                $custom = get_post_custom(get_the_ID());
              echo '<div class="col-md-4 col-sm-4">
                    <div class="grid-item staff-item"> 
                        <div class="grid-item-inner">';
                if (has_post_thumbnail()):
                    echo '<div class="media-box"><a href="'.get_permalink(get_the_ID()).'">';
                     echo get_the_post_thumbnail(get_the_ID(), 'full');
                    echo '</a></div>';
                endif;
				$job_title = get_post_meta(get_the_ID(),'imic_staff_job_title',true);
				$job = '';
				if(!empty($job_title)) { $job = '<div class="meta-data">'.$job_title.'</div>'; }
                echo '<div class="grid-content">
                                <h3> <a href="'.get_permalink(get_the_ID()).'">'. get_the_title() . '</a></h3>';
				echo $job;
               $staff_icons = get_post_meta(get_the_ID(), 'imic_social_icon_list', false);
               echo imic_social_staff_icon();
                $description = imic_excerpt();
                if (!empty($description)) {
                    echo $description;
                }
                echo'</div></div>
                    </div>
                </div>';
            endwhile;
			endif; ?>

            <div class="row">
                <?php echo $content ?>
            </div>

            <?php
			echo '<div class="clear"></div>';
            if(function_exists("pagination")) {
                pagination();
                }
				wp_reset_query();
        ?>
	</div>
            <?php  imic_woocommerce_sidebar($variable_post_id,3); ?>
    	</div>
     </div>
<?php get_footer(); ?>