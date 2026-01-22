<?php
global $imic_options;
if(!is_front_page()){
     echo '</div></div>';
}
?>
<?php $options = get_option('imic_options'); ?>
<!-- Start Footer -->
<?php if ( is_active_sidebar( 'footer-sidebar' ) ) : ?>
<footer class="site-footer">
    <div class="container">
        <div class="row">
        	<?php dynamic_sidebar('footer-sidebar'); ?>
        </div>
    </div>
</footer>
<?php endif; ?>
<footer class="site-footer-bottom">
    <div class="container">
        <div class="row">
            <?php
            if (!empty($options['footer_copyright_text'])) {
                echo '<div class="copyrights-col-left col-md-8 col-sm-8">'; ?>
                <p><?php _e('&copy; ','framework'); echo date('Y '); bloginfo('name'); _e('. ','framework'); echo $options['footer_copyright_text']; ?> | <a href="mailto:info@nittanyvalleychildrenschoir.org">info@nittanyvalleychildrenschoir.org</a></p>
                <?php echo '</div>';
            }
            ?> 
            <div class="copyrights-col-right col-md-4 col-sm-4">
                
            </div>
        </div>
    </div>
</footer>
<?php if ($options['enable_backtotop'] == 1) { 
echo'<a id="back-to-top"><i class="fa fa-angle-double-up"></i></a>';
 } ?>
</div>
<!-- End Boxed Body -->
<?php wp_footer(); ?>
</body>
</html>