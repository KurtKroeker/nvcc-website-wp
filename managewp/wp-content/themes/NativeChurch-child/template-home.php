<?php
/*
  Template Name: Home
 */
get_header();
$custom_home = get_post_custom(get_the_ID());
$home_id = get_the_ID();
/* Start Hero Slider */
get_template_part('flex-slider');
/* End Hero Slider */
/** Upcoming Events Loop ** */
$temp_wp_query = clone $wp_query;
$today = date('Y-m-d');
$currentTime = date(get_option('time_format'));
$upcomingEvents = '';
$imic_events_to_show_on = get_post_meta(get_the_ID(),'imic_events_to_show_on',true);
//                Modified Data 
$imic_events_to_show_on=!empty($imic_events_to_show_on)?$imic_events_to_show_on:4;
//                Modified Data end
query_posts(array('post_type' => 'event', 'meta_key' => 'imic_event_start_dt', 'meta_query' => array(array('key' => 'imic_event_frequency_end', 'value' => $today, 'compare' => '>=')), 'orderby' => 'meta_value', 'order' => 'ASC', 'posts_per_page' => -1));
$event_add = array();
$sinc = 1;
if (have_posts()):
    while (have_posts()):the_post();
        $eventDate = strtotime(get_post_meta(get_the_ID(), 'imic_event_start_dt', true));
        $eventTime = get_post_meta(get_the_ID(), 'imic_event_start_tm', true);
        if ($eventTime == '') {
            $eventTime = "";
        }
		$eventTime = strtotime($eventTime);
		if($eventTime!='') { $eventTime = date('h:i A',$eventTime); }
        $frequency = get_post_meta(get_the_ID(), 'imic_event_frequency', true);
        $frequency_count = '';
        $frequency_count = get_post_meta(get_the_ID(), 'imic_event_frequency_count', true);
        if ($frequency > 0) {
            $frequency_count = $frequency_count;
        } else { $frequency_count = 0; }
        $seconds = $frequency * 86400;
        $fr_repeat = 0;
        while ($fr_repeat <= $frequency_count) {
            $eventDate = get_post_meta(get_the_ID(), 'imic_event_start_dt', true);
            $eventDate = strtotime($eventDate);
			if($frequency==30) {
			$eventDate = strtotime("+".$fr_repeat." month", $eventDate);
			$eventDate = date('Y-m-d',$eventDate);
			$eventDate = $eventDate.' '.$eventTime;
			$eventDate = strtotime($eventDate);
			}
			else {
            $new_date = $seconds * $fr_repeat;
            $eventDate = $eventDate + $new_date;
			$eventDate = date('Y-m-d',$eventDate);
			$eventDate = $eventDate.' '.$eventTime;
			$eventDate = strtotime($eventDate);
			}
            $date_sec = date('Y-m-d', $eventDate);
            $exact_time = strtotime($date_sec . ' ' . $eventTime);
            if ($exact_time >= date('U')) {
                $event_add[$eventDate + $sinc] = get_the_ID();
                $sinc++;
            } $fr_repeat++;
        }
    endwhile;
    $nos_event = 1;
    ksort($event_add);
    foreach ($event_add as $key => $value) {
       $eventTime = get_post_meta($value, 'imic_event_start_tm', true);
		$eventTime = strtotime($eventTime);
		$counter_time = date('h:i A',$eventTime);
		if($eventTime!='') { $eventTime = date_i18n(get_option('time_format'),$eventTime); }
        $recurrence = get_post_meta($value, 'imic_event_frequency', true);
        if ($recurrence > 0) {
            $icon = ' <i class="fa fa-refresh" style="font-size:80%;" title="Recurring"></i>';
        } else {
            $icon = '';
        }
        $stime = '';
        $setime = '';
        if ($eventTime != '') {
            $stime = ' | ' . $eventTime;
            $setime = $eventTime;
        }
        $date_converted=date('Y-m-d',$key );
        $custom_event_url =imic_query_arg($date_converted,$value);  
        if ($nos_event == 1) {
            $firstEventTitle = get_the_title($value);
            $firstEventURL = $custom_event_url;
            $date_timer_event = date('Y-m-d', $key);
            $unix_time = strtotime($date_timer_event . ' ' . $setime);
            $time_timer_event = date('G:i', $unix_time);
            $firstEventDate = date_i18n( get_option( 'date_format' ),$key);
            
           
            $firstEventDateData = date('F j, Y', $key) . ' ' . $counter_time;
           
        }
       
        $upcomingEvents .= '<li class="item event-item">
                        <div class="event-date"> <span class="date">' . date_i18n('d', $key) . '</span> <span class="month">'.imic_global_month_name($key).'</span> </div>
                         <div class="event-detail">
                        
                        <h4><a href="' . $custom_event_url . '">' . get_the_title($value) . ' ' . $icon . '</a></h4>
                        <span class="event-dayntime meta-data">' .date_i18n('l', $key) . $stime . '</span> </div>
									<div class="to-event-url">
									<div><a href="'.$custom_event_url.'" class="btn btn-default btn-sm">'.__('Details','framework').'</a></div>
									</div>
								</li>';
        if (++$nos_event > $imic_events_to_show_on)
            break;
    }
else:
   $no_upcoming_events_msg = '<div class="notice-bar">
            <div class="container">
                <div class="row">
                    <div class="col-md-3 col-sm-4 hidden-xs">
                        
                    </div>'.__('No Upcoming Events Found', 'framework').'</div></div>';
endif;
$wp_query = clone $temp_wp_query;
?>
<!-- Start Notice Bar -->
<?php
 $imic_latest_sermon_events = get_post_meta($home_id, 'imic_latest_sermon_events_to_show_on', true);
$imic_all_event_sermon_url= get_post_meta($home_id, 'imic_all_event_sermon_url', true);
 $imic_upcoming_events_area = get_post_meta($home_id,'imic_upcoming_area',true);
if($imic_upcoming_events_area==1) {
if ((!empty($firstEventTitle) && $imic_latest_sermon_events == 'letest_event')||(!empty($firstEventTitle) && $imic_latest_sermon_events=='')) {
    ?>
    <div class="notice-bar">
        <div class="container">
            <div class="row">
                <div class="col-md-3 col-sm-6 col-xs-6 notice-bar-title"> <span class="notice-bar-title-icon hidden-xs"><i class="fa fa-calendar fa-3x"></i></span> <span class="title-note"><?php _e('Next', 'framework'); ?></span> <strong><?php _e('Upcoming Event', 'framework'); ?></strong> </div>
                <div class="col-md-3 col-sm-6 col-xs-6 notice-bar-event-title">
                  <h5><a href="<?php echo $firstEventURL; ?>"><?php echo $firstEventTitle; ?></a></h5>
                    <span class="meta-data"><?php echo $firstEventDate; ?></span> </div>
                <div id="counter" class="col-md-4 col-sm-6 col-xs-12 counter" data-date="<?php echo strtotime($firstEventDateData); ?>">
                    <div class="timer-col"> <span id="days"></span> <span class="timer-type"><?php _e('days', 'framework'); ?></span> </div>
                    <div class="timer-col"> <span id="hours"></span> <span class="timer-type"><?php _e('hrs', 'framework'); ?></span> </div>
                    <div class="timer-col"> <span id="minutes"></span> <span class="timer-type"><?php _e('mins', 'framework'); ?></span> </div>
                    <div class="timer-col"> <span id="seconds"></span> <span class="timer-type"><?php _e('secs', 'framework'); ?></span> </div>
                </div>
                <?php
                $pages_e = get_pages(array(
                    'meta_key' => '_wp_page_template',
                    'meta_value' => 'template-events.php'
                ));
               if(!empty($imic_all_event_sermon_url)||!empty($pages_e[0]->ID)){
                    $imic_all_event_sermon_url = !empty($imic_all_event_sermon_url) ? $imic_all_event_sermon_url: get_permalink($pages_e[0]->ID);
                ?>
                    <div class="col-md-2 col-sm-6 hidden-xs"> <a href="<?php echo $imic_all_event_sermon_url ?>" class="btn btn-primary btn-lg btn-block"><?php _e('All Events', 'framework'); ?></a> </div>
                <?php } ?>
            </div>
        </div>
    </div>
<?php } else {
    $posts = get_posts(array('post_type' => 'sermons', 'post_status' => 'publish', 'suppress_filters' => false, 'posts_per_page' => 1));
    if (!empty($posts[0]->ID)) {
        ?>
        <div class="notice-bar latest-sermon">
            <div class="container">
                <div class="row">
                    <div class="col-md-3 col-sm-4 hidden-xs">
                        <h3><i class="fa fa-microphone"></i> <?php _e('Latest Sermon', 'framework'); ?></h3>
                    </div>
                    <?php
                    foreach ($posts as $post) {
                      $attach_full_audio= imic_sermon_attach_full_audio($post->ID);
                      echo '<div class="col-md-7 col-sm-8 col-xs-12">';
                      if(!empty($attach_full_audio)) {
                  echo '<audio class="audio-player" src="' . $attach_full_audio . '" type="audio/mp3" controls></audio>';
        	}
                        else{
                            echo '<h2><a href="'.get_permalink($post->ID).'">'.$post->post_title.'</a></h2>';
                           }
                       echo '</div>';
                        $pages_s = get_pages(array(
                            'meta_key' => '_wp_page_template',
                            'meta_value' => 'template-sermons.php'
                        ));
                       if(!empty($imic_all_event_sermon_url)||!empty($pages_s[0]->ID)){
                        $imic_all_event_sermon_url = !empty($imic_all_event_sermon_url) ? $imic_all_event_sermon_url: get_permalink($pages_s[0]->ID);
                        echo'<div class="col-md-2 hidden-sm hidden-xs">
        	<a href="' . $imic_all_event_sermon_url . '" class="btn btn-block btn-primary">' . __('All Sermons', 'framework') . '</a>
        </div>';
                    }}
                    ?>
                </div>
            </div>
        </div>
    <?php }
}}
?>
<!-- End Notice Bar --> 
<!-- Start Content -->
<div class="main" role="main">
 <div id="content" class="content full">
    <div class="container">
       <?php  wp_reset_query();
              the_content();		
               ?>
            <div class="row"> 
                <?php $imic_featured_block_area = get_post_meta($home_id,'imic_imic_featured_blocks',true); 
					if($imic_featured_block_area==1) { ?>
                <!-- Start Featured Blocks -->
                <div class="featured-blocks clearfix">
                    <?php
                    $imic_home_featured_blocks = $custom_home['imic_home_featured_blocks'][0];
                    $imic_home_featured_blocks_id = explode(',', $imic_home_featured_blocks);
                    $i = 1;
                    foreach ($imic_home_featured_blocks_id as $f_id) {
                        $imic_custom_read_more = $custom_home['imic_home_featured_blocks' . $i][0];
                        imic_get_data_by_path($f_id, $imic_custom_read_more);
                        $i++;
                    }
                    ?>
                </div>
                <!-- End Featured Blocks --> 
                 <?php } ?>
            </div>
            <div class="row">
                <div class="col-md-8 col-sm-6"> 
                    <?php $imic_recent_events_area = get_post_meta($home_id,'imic_imic_upcoming_events',true); 
				if($imic_recent_events_area==1) { ?>
                    <!-- Events Listing -->
                    <div class="listing events-listing">
                        <header class="listing-header">
                            <?php
                            $imic_custom_upcoming_events_title = !empty($custom_home['imic_custom_upcoming_events_title'][0]) ? $custom_home['imic_custom_upcoming_events_title'][0] : __('More Coming Events', 'framework');
                            echo '<h3>' . $imic_custom_upcoming_events_title . '</h3>';
                            ?>
                        </header>
                        <section class="listing-cont">
                            <ul>
                                <?php echo $upcomingEvents;
                                if(isset($no_upcoming_events_msg)):
                                echo '<li>'.$no_upcoming_events_msg.'</li>';
                                endif;
                                ?>
                            </ul>
                        </section>
                    </div>
                    <div class="spacer-30"></div>
                    <?php } ?>
                    <!-- Latest News -->
                    <?php
                    $posts_per_page = get_post_meta($home_id, 'imic_posts_to_show_on', true);
			$imic_recent_post_area = get_post_meta($home_id,'imic_imic_recent_posts',true);
					  if($imic_recent_post_area==1) {
                                            
                    if ($posts_per_page == '' ){$posts_per_page = 2;}
                    $temp_wp_query = clone $wp_query;
                    query_posts(array(
                        'post_type' => 'post',
                        'posts_per_page' => $posts_per_page,
                    ));
                    if (have_posts()):
                     ?>
                        <div class="listing post-listing">
                            <header class="listing-header">
                                <?php
                                $imic_custom_latest_news_title = !empty($custom_home['imic_custom_latest_news_title'][0]) ? $custom_home['imic_custom_latest_news_title'][0] : __('Latest News', 'framework');
                                echo'<h3>' . $imic_custom_latest_news_title . '</h3>';
                                ?>
                            </header>
                            <section class="listing-cont">
                                <ul>
                                    <?php
                                    while (have_posts()):the_post();
                                        if ('' != get_the_post_thumbnail()) {
                                            $class = "col-md-8";
                                        } else {
                                            $class = "col-md-12";
                                        }
                                        ?>
                                        <li class="item post">
                                            <div class="row">
                                                <?php
                                                if (has_post_thumbnail()):
                                                    echo '<div class="col-md-4">
                                                        <a href="' . get_permalink() . '" class="media-box">';
                                                    the_post_thumbnail('full');
                                                    echo '</a>
                                                </div>';
                                                endif;
                                                ?>
                                                <div class="<?php echo $class; ?>">
                                                    <div class="post-title">
                                                        <?php
                                                        echo '<h2><a href="' . get_permalink() . '">' . get_the_title() . '</a></h2>';
                                                        echo '<span class="meta-data"><i class="fa fa-calendar"></i>' . __('on ', 'framework') . get_the_time(get_option('date_format')) . '</span></div>';
                                                        echo imic_excerpt(50);
                                                        ?>
                                                    </div>
                                                </div>
                                        </li>
                                    <?php endwhile; ?>
                                </ul>
                            </section>
                        </div>
                        <?php
                    endif;
                    $wp_query = clone $temp_wp_query;}
                    ?>
                </div>
                <!-- Start Sidebar -->
                <div class="col-md-4 col-sm-6"> 
                    <?php dynamic_sidebar('home-page-sidebar'); ?>
                </div>
                <!-- End Sidebar -->
            </div>
        </div>
    </div>
</div>
<!-- Start Featured Gallery -->
<?php
$imic_imic_galleries = get_post_meta($home_id,'imic_imic_galleries',true);
$posts_per_page = get_post_meta($home_id,'imic_galleries_to_show_on',true);
$posts_per_page=!empty($posts_per_page)?$posts_per_page:3;
$temp_wp_query = clone $wp_query;
query_posts(array(
    'post_type' => 'gallery',
    'posts_per_page' => $posts_per_page,
));
if (have_posts()&&$imic_imic_galleries==1):
    ?>
    <div class="featured-gallery">
        <div class="container">
            <div class="row">
                <?php
                echo '<div class="col-md-3 col-sm-3">';
                $imic_custom_gallery_title = !empty($custom_home['imic_custom_gallery_title'][0]) ? $custom_home['imic_custom_gallery_title'][0] : __('Updates from our gallery', 'framework');
                echo'<h4>' . $imic_custom_gallery_title . '</h4>';
                $imic_custom_more_galleries_title = !empty($custom_home['imic_custom_more_galleries_title'][0]) ? $custom_home['imic_custom_more_galleries_title'][0] : __('More Galleries', 'framework');
                $pages = get_pages(array(
                    'meta_key' => '_wp_page_template',
                    'meta_value' => 'template-gallery-pagination.php'
                ));
                $imic_custom_more_galleries_url = !empty($custom_home['imic_custom_more_galleries_url'][0]) ? $custom_home['imic_custom_more_galleries_url'][0] : get_permalink($pages[0]->ID);
                echo'<a href="' . $imic_custom_more_galleries_url . '" class="btn btn-default btn-lg">' . $imic_custom_more_galleries_title . '</a>';
                echo '</div>';
                while (have_posts()):the_post();
                    $custom = get_post_custom(get_the_ID());
                      //$imic_gallery_images=get_post_meta(get_the_ID(),'imic_gallery_images',true);
					if(!empty($imic_gallery_images)) { $gallery_img = $imic_gallery_images; } else { $gallery_img = ''; }
                   
                                        if (has_post_thumbnail() || count($custom['imic_gallery_images']) > 0):
                        $post_format_temp = get_post_format();
                        $post_format = !empty($post_format_temp) ? $post_format_temp : 'image';
                        echo '<div class="col-md-3 col-sm-3 post format-' . $post_format . '">';
                        switch (get_post_format()) {
                            case 'image':
                                $large_src_i = wp_get_attachment_image_src($custom['_thumbnail_id'][0], 'full');
                                echo'<a href="' . $large_src_i[0] . '" data-rel="prettyPhoto[Gallery]" class="media-box">';
                                the_post_thumbnail('full');
                                echo'</a>';
                                break;
                            case 'gallery':
                                echo '<div class="media-box">';
                              imic_gallery_flexslider(get_the_ID());
                                if (count($custom['imic_gallery_images']) > 0) {
                                    echo'<ul class="slides">';
                                    foreach ($custom['imic_gallery_images'] as $custom_gallery_images) {
                                        $large_src = wp_get_attachment_image_src($custom_gallery_images, 'full');
                                        echo'<li class="item"><a href="' . $large_src[0] . '" data-rel="prettyPhoto[' . get_the_title() . ']">';
                                        echo wp_get_attachment_image($custom_gallery_images, 'full');
                                        echo'</a></li>';
                                    }
                                    echo'</ul>';
                                }
                                echo'</div>
                                </div>';
                                break;
                            case 'link':
                                if (!empty($custom['imic_gallery_link_url'][0])) {
                                    echo '<a href="' . $custom['imic_gallery_link_url'][0] . '" target="_blank" class="media-box">';
                                    the_post_thumbnail('full');
                                    echo'</a>';
                                }
                                break;
                            case 'video':
                                if (!empty($custom['imic_gallery_video_url'][0])) {
                                    echo '<a href="' . $custom['imic_gallery_video_url'][0] . '" data-rel="prettyPhoto[Gallery]" class="media-box">';
                                    the_post_thumbnail('full');
                                    echo'</a>';
                                }
                                break;
                            default:
                                $_thumbnail_id=get_post_meta(get_the_ID(),'_thumbnail_id',true);
                                $large_src_i = wp_get_attachment_image_src($_thumbnail_id, 'full');
                                echo'<a href="' . $large_src_i[0] . '" data-rel="prettyPhoto[Gallery]" class="media-box">';
                                the_post_thumbnail('full');
                                echo'</a>';
                                break;
                        }
                        echo'</div>';
                    endif;
                endwhile;
                ?>
            </div>
        </div>
    </div>
    <?php 
endif; 
$wp_query = clone $temp_wp_query;
//-- End Featured Gallery --
get_footer();
?>