<?php
get_header();
$imic_options = get_option('imic_options');
?>
<!-- start page section -->
<section class="page-section">
    <div class="container">
        <div class="row">
            <!-- start post -->
            <article class="col-md-12">
                <section class="page-content">
                    <?php
                    while (have_posts()) : the_post();
                        the_content();
                    endwhile;
                    ?>
                </section>
                <?php if (isset($imic_options['switch_sharing']) && $imic_options['switch_sharing'] == 1 && $imic_options['share_post_types']['2'] == '1') { ?>
                    <?php imic_share_buttons(); ?>
                <?php } ?>
            </article>
            <!-- end post -->
        </div>
    </div>
</section>
<?php get_footer(); ?>