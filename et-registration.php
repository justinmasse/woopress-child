<?php
/**
 * Template Name: Custom Registration Page
 */
extract(etheme_get_page_sidebar());
//Check whether the user is already logged in
if (!$user_ID) {
        extract(etheme_get_page_sidebar());
        get_header();
    	
        ?>

            <script type="text/javascript" src="http://52.25.171.130/wp-includes/js/jquery/ui/core.min.js"></script>
            <script type="text/javascript" src="http://52.25.171.130/wp-includes/js/jquery/ui/datepicker.min.js"></script>

            <div class="page-heading bc-type-<?php echo esc_attr(etheme_option('breadcrumb_type')); ?>">
                <div class="container">
                    <div class="row">
                        <div class="col-md-12 a-center">
                            <h1 class="title"><span><?php the_title(); ?></span></h1>
                            <?php etheme_breadcrumbs(); ?>
                        </div>
                    </div>
                </div>
            </div>

            <div class="container et-registration">
                <div class="page-content sidebar-position-<?php echo esc_attr($position); ?> responsive-sidebar-<?php echo esc_attr($responsive); ?>">
                    <div class="row">
                        <?php if($position == 'left' || ($responsive == 'top' && $position == 'right')): ?>
                            <div class="<?php echo esc_attr($sidebar_span); ?> sidebar sidebar-left">
                                <?php etheme_get_sidebar($sidebarname); ?>
                            </div>
                        <?php endif; ?>

                        <div class="content <?php echo $content_span; ?>">
                               <?php
                                if(get_option('users_can_register')) {
                                    ?>
                                    <div class="row">
                                        
                                        <div class="col-md-6">
                                            <div class="content-box">
                                                <h3 class="title"><span><?php _e('Create Account', ETHEME_DOMAIN); ?></span></h3>
                                                <div id="result"></div> 

                                                <?php 
                                                $rand = rand(100,1000);
                                                echo '1';
                                                $captcha_instance = new ReallySimpleCaptcha();
                                                echo '2';
                                                $captcha_instance->bg = array( 229, 83, 76 );
                                                echo '3';
                                                $word = $captcha_instance->generate_random_word();
                                                echo '4';
                                                $prefix = mt_rand();
                                                echo '5';
                                                $img_name = $captcha_instance->generate_image( $prefix, $word );
                                                echo '6';
                                                $captcha_img = ETHEME_CODE_URL.'/inc/really-simple-captcha/tmp/'.$img_name;
                                                echo '7';
                                                ?>
                                                    <form class="et-register-form form-<?php echo $rand; ?>" action="" method="get">
                                                        <div id="register-popup-result"></div> 
                                                        <div class="login-fields">
                                                            <p class="form-row">
                                                                <label class=""><?php _e( "Enter your username", ETHEME_DOMAIN ) ?> <span class="required">*</span></label>
                                                                <input type="text" name="username" class="text input-text" />
                                                            </p>
                                                            <p class="form-row">
                                                                <label class=""><?php _e( "Enter your E-mail address", ETHEME_DOMAIN ) ?> <span class="required">*</span></label>
                                                                <input type="text" name="email" class="text input-text" />
                                                            </p>
                                                            <p class="form-row">
                                                                <label class=""><?php _e( "Enter your password", ETHEME_DOMAIN ) ?> <span class="required">*</span></label>
                                                                <input type="password" name="et_pass" class="text input-text" />
                                                            </p>
                                                            <p class="form-row">
                                                                <label class=""><?php _e( "Re-enter your password", ETHEME_DOMAIN ) ?> <span class="required">*</span></label>
                                                                <input type="password" name="et_pass2" class="text input-text" />
                                                            </p>
                                                        </div>
                                                        <div class="captcha-block">
                                                            <img src="<?php echo $captcha_img; ?>">
                                                            <input type="text" name="captcha-word" class="captcha-input">
                                                            <input type="hidden" name="captcha-prefix" value="<?php echo $prefix; ?>">
                                                        </div>
                                                        <p class="form-row right">
                                                            <input type="hidden" name="et_register" value="1">
                                                            <button class="btn btn-black big text-center submitbtn" type="submit"><span><?php _e( "Register", ETHEME_DOMAIN ) ?></span></button>
                                                        </p>
                                                    </form>
                                                    <script type="text/javascript">
                                                        jQuery(function($){
                                                            $('.form-<?php echo $rand; ?>').submit(function(e) {
                                                                e.preventDefault();
                                                                $('.form-<?php echo $rand; ?> div#register-popup-result').html('<img src="<?php echo get_template_directory_uri(); ?>/images/loading.gif" class="loader" />').fadeIn();
                                                                var input_data = $(this).serialize();
                                                                input_data += '&action=et_register_action';
                                                                $.ajax({
                                                                    type: "GET",
                                                                    dataType: "JSON",
                                                                    url: "<?php echo admin_url( 'admin-ajax.php' ); ?>",
                                                                    data: input_data,
                                                                    success: function(response){
                                                                        $('.loader').remove();
                                                                        if(response.status == 'error') {
                                                                            var msgHtml = '<span class="error">' + response.msg + '</span>';
                                                                            $('<div>').html(msgHtml).appendTo('.form-<?php echo $rand; ?> div#register-popup-result').hide().fadeIn('slow');
                                                                            
                                                                        } else {
                                                                            var msgHtml = '<span class="success">' + response.msg + '</span>';
                                                                            $('<div>').html(msgHtml).appendTo('.form-<?php echo $rand; ?> div#register-popup-result').hide().fadeIn('slow');
                                                                            $(this).find("input[type=text], input[type=password], textarea").val("");
                                                                        }
                                                                    }
                                                                });
                                                                
                                                            });
                                                        }, jQuery);
                                                    </script>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <?php 
												if (have_posts()) :
												   while (have_posts()) :
												      the_post();
												      the_content();
												   endwhile;
												endif;
											 ?>
                                        </div>

                                    </div>

                                    <?php
                                }
                                else _e( '<span class="error">Registration is currently disabled. Please try again later.<span>', ETHEME_DOMAIN );
                                ?>
                        </div>

                        <?php if($position == 'right' || ($responsive == 'bottom' && $position == 'left')): ?>
                            <div class="<?php echo esc_attr($sidebar_span); ?> sidebar sidebar-right">
                                <?php etheme_get_sidebar($sidebarname); ?>
                            </div>
                        <?php endif; ?>
                    </div>

                </div>
            </div>


        <?php
        get_footer();
}
else {
    echo "<script type='text/javascript'>window.location='". home_url() ."'</script>";
}
?>