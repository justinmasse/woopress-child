<?php add_action( 'wp_enqueue_scripts', 'theme_enqueue_styles' );
function theme_enqueue_styles() {
	$script_depends = array();

    if(class_exists('WooCommerce')) {
        $script_depends = array('wc-add-to-cart-variation');
    }

    wp_enqueue_style( 'child-style', get_stylesheet_uri(), array( 'bootstrap', 'parent-style' ) );
    wp_enqueue_script('child-etheme', get_stylesheet_uri().'/../js/etheme.js',$script_depends,false,true);
}

require_once( get_stylesheet_directory() . '/framework/init.php' );

function et_get_main_menu($menu_id = 'main-menu') {
    $custom_menu = etheme_get_custom_field('custom_nav');
    $one_page_menu = '';
    if(etheme_get_custom_field('one_page')) $one_page_menu = ' one-page-menu';

    //one page nav
    if(!empty($custom_menu) && $custom_menu != '') {
        $output = false;
        $output = wp_cache_get( $custom_menu, 'et_get_main_menu' );
        if ( !$output ) {
        	$output = '<div class = "menu-main-container">';
	            ob_start(); 
	            
	            wp_nav_menu(array(
	                'menu' => $custom_menu,
	                'before' => '',
	                'container_class' => 'menu-main-container'.$one_page_menu,
	                'after' => '',
	                'link_before' => '',
	                'link_after' => '',
	                'depth' => 4,
	                'fallback_cb' => false,
	                'walker' => new Et_Navigation
	            ));
	            
	        	$output .= get_accountLinks();
	            $output .= ob_get_contents();
	            
        	$output .= '</div>';
            ob_end_clean();
            
            wp_cache_add( $custom_menu, $output, 'et_get_main_menu' );
        }
        
        echo $output;
        return;
    }


    //multi page nav
    if ( has_nav_menu( $menu_id ) ) {
        $output = false;
        $output = wp_cache_get( $menu_id, 'et_get_main_menu' );
        if ( !$output ) {
        	$output = '<div class = "menu-main-container">';
	            ob_start(); 
	            
	            wp_nav_menu(array(
	                'theme_location' => $menu_id,
	                'before' => '',
	                'after' => '',
	                'container' => false,
	                'link_before' => '',
	                'link_after' => '',
	                'depth' => 4,
	                'fallback_cb' => false,
	                'walker' => new Et_Navigation
	            ));

	            $output .= get_accountLinks();
	            $output .= ob_get_contents();
	            
        	$output .= '</div>';

            ob_end_clean();
            
            wp_cache_add( $menu_id, $output, 'et_get_main_menu' );
        }
        
        echo $output;
    } else {
        ?>
            <br>
            <h4 class="a-center">Set your main menu in <em>Appearance &gt; Menus</em></h4>
        <?php
    }
}

function et_get_mobile_menu($menu_id = 'mobile-menu') {
        
    if ( has_nav_menu( $menu_id ) ) {
        $output = false;
        $output = wp_cache_get( $menu_id, 'et_get_mobile_menu' );
        $one_page_menu = '';
        if(etheme_get_custom_field('one_page')) $one_page_menu = ' one-page-menu';
        if ( !$output ) {
            ob_start(); 
            
            wp_nav_menu(array(
                'container_class' => $one_page_menu,
                'theme_location' => 'mobile-menu',
                'walker' => new Et_Navigation_Mobile,
                'menu_class' => 'links',
            )); 
            
            $output = ob_get_contents();
            ob_end_clean();
            
            wp_cache_add( $menu_id, $output, 'et_get_mobile_menu' );
        }
        
        echo $output;
    } else {
        ?>
            <br>
            <h4 class="a-center">Set your main menu in <em>Appearance &gt; Menus</em></h4>
        <?php
    }
}


function get_accountLinks() {
	//temp fix
	$popups = false;

	$content = '<ul class="account-menu">';
			if(etheme_get_option('top_links')):
            if ( is_user_logged_in() ) :
                if(class_exists('Woocommerce')): 
                	$content .= '<li class="my-account-link"><a href="';
                	$content .= get_permalink( get_option('woocommerce_myaccount_page_id') );
                	$content .= '">My Account</a></li>';
                endif;
                $content .= '<li class="logout-link"><a href="';
                $content .= wp_logout_url(home_url());
                $content .= '">Logout</a></li>';
            else :
                $reg_id = etheme_tpl2id('et-registration.php'); 
                $reg_url = get_permalink($reg_id);
                if(class_exists('Woocommerce')):
                	$content .= '<li class="login-link">';
                		$content .= '<a href="';
                		$content .= get_permalink( get_option('woocommerce_myaccount_page_id') );
                		$content .= '">Sign In</a>';
                		if($popups):
							$content .= '<div class="login-popup">';
								$content .= '<div class="popup-title">';
									$content .= '<span>Login Form</span>';
								$content .= '</div>';

                                    $content .= '<form method="post" class="login" action="';
                                    $content .= get_the_permalink( get_option( 'woocommerce_myaccount_page_id' ) );
                                    $content .= '">';

                                        do_action( 'woocommerce_login_form_start' );

                                        $content .= '<p class="form-row form-row-first">';
                                            $content .= '<label for="username">Username or email<span class="required">*</span></label>';
                                            $content .= '<input type="text" class="input-text" name="username" id="username" />';
                                        $content .= '</p>';
                                        $content .= '<p class="form-row form-row-last">';
                                            $content .= '<label for="password">Password<span class="required">*</span></label>';
                                            $content .= '<input class="input-text" type="password" name="password" id="password" />';
                                        $content .= '</p>';
                                        $content .= '<div class="clear"></div>';

                                        do_action( 'woocommerce_login_form' );

                                        $content .= '<p class="form-row">';
                                            wp_nonce_field( 'woocommerce-login' );
                                            $content .= '<input type="submit" class="button" name="login" value="Login" />';
                                        $content .= '</p>';

                                        $content .= '<div class="clear"></div>';

                                        do_action( 'woocommerce_login_form_end' );

                                    $content .= '</form>';

							$content .= '</div>';
						endif;
            		$content .= '</li>';
            	endif;
                $content .= '<li class="register-link">';
                        $content .= '<a href="';
                        $content .= $reg_url;
                        $content .= '">Register</a>';
                        if($popups) :
                            $content .= '<div class="register-popup">';
                                $content .= '<div class="popup-title">';
                                    $content .= '<span>Register Form</span>';
                                $content .= '</div>';
                                et_register_form();
                            $content .= '</div>';
                        endif;
                    $content .= '</li>';	
            endif;
        endif;
	$content .= '</ul>';
 	return $content;
}

function custom_etheme_top_links($args = array()) {
	extract(shortcode_atts(array(
		'popups'  => true
	), $args));
        ?>
        <ul class="links">
      			<?php if(etheme_get_option('top_links')): ?>
                <?php if ( is_user_logged_in() ) : ?>
                    <?php if(class_exists('Woocommerce')): ?> 
                    	<li class="my-account-link"><a href="<?php echo get_permalink( get_option('woocommerce_myaccount_page_id') ); ?>"><?php _e( 'My Account', ETHEME_DOMAIN ); ?></a></li>
                    <?php endif; ?>
                    <li class="logout-link"><a href="<?php echo wp_logout_url(home_url()); ?>"><?php _e( 'Logout', ETHEME_DOMAIN ); ?></a></li>
                <?php else : ?>
                    <?php 
                        $reg_id = etheme_tpl2id('et-registration.php'); 
                        $reg_url = get_permalink($reg_id);
                    ?>    
                    <?php if(class_exists('Woocommerce')): ?>
                    	<li class="login-link">
                    		<a href="<?php echo get_permalink( get_option('woocommerce_myaccount_page_id') ); ?>"><?php _e( 'Sign In', ETHEME_DOMAIN ); ?></a>
                    		<?php if($popups) : ?>
								<div class="login-popup">
									<div class="popup-title">
										<span><?php _e( 'Login Form', ETHEME_DOMAIN ); ?></span>
									</div>

                                        <form method="post" class="login" action="<?php echo get_the_permalink( get_option( 'woocommerce_myaccount_page_id' ) ); ?>">

                                            <?php do_action( 'woocommerce_login_form_start' ); ?>

                                            <p class="form-row form-row-first">
                                                <label for="username"><?php _e( 'Username or email', 'woocommerce' ); ?> <span class="required">*</span></label>
                                                <input type="text" class="input-text" name="username" id="username" />
                                            </p>
                                            <p class="form-row form-row-last">
                                                <label for="password"><?php _e( 'Password', 'woocommerce' ); ?> <span class="required">*</span></label>
                                                <input class="input-text" type="password" name="password" id="password" />
                                            </p>
                                            <div class="clear"></div>

                                            <?php do_action( 'woocommerce_login_form' ); ?>

                                            <p class="form-row">
                                                <?php wp_nonce_field( 'woocommerce-login' ); ?>
                                                <input type="submit" class="button" name="login" value="<?php _e( 'Login', 'woocommerce' ); ?>" />
                                            </p>

                                            <div class="clear"></div>

                                            <?php do_action( 'woocommerce_login_form_end' ); ?>

                                        </form>

								</div>
							<?php endif; ?>
                		</li>
                	<?php endif; ?>
                    <?php if(!empty($reg_id)): ?>
                    	<li class="register-link">
                    		<a href="<?php echo $reg_url; ?>"><?php _e( 'Register', ETHEME_DOMAIN ); ?></a>
                    		<?php if($popups) : ?>
								<div class="register-popup">
									<div class="popup-title">
										<span><?php _e( 'Register Form', ETHEME_DOMAIN ); ?></span>
									</div>
									<?php et_register_form(); ?>
								</div>
							<?php endif; ?>
                    	</li>
                	<?php endif; ?>
                <?php endif; ?>
            <?php endif; ?>
		</ul>
    <?php
}

//footer widget areas

function aone_register_sidebars($args){
    register_sidebar( $args ); 
}
aone_register_sidebars(array(
    'name'          => 'Footer Column One',
    'id'            => 'footer-column-one',
    'description'   => '',
        'class'         => '',
    'before_widget' => '',
    'after_widget'  => '',
    'before_title'  => '<h2 class="widgettitle">',
    'after_widgetitle'   => '</h2>' )
);
aone_register_sidebars(array(
    'name'          => 'Footer Column Two',
    'id'            => 'footer-column-two',
    'description'   => '',
        'class'         => '',
    'before_widget' => '',
    'after_widget'  => '',
    'before_title'  => '<h2 class="widgettitle">',
    'after_widgetitle'   => '</h2>' )
);
aone_register_sidebars(array(
    'name'          => 'Footer Column Three',
    'id'            => 'footer-column-threee',
    'description'   => '',
        'class'         => '',
    'before_widget' => '',
    'after_widget'  => '',
    'before_title'  => '<h2 class="widgettitle">',
    'after_widgetitle'   => '</h2>' )
);
aone_register_sidebars(array(
    'name'          => 'Footer Column Four',
    'id'            => 'footer-column-four',
    'description'   => '',
        'class'         => '',
    'before_widget' => '',
    'after_widget'  => '',
    'before_title'  => '<h2 class="widgettitle">',
    'after_widgetitle'   => '</h2>' )
);

// remove theme side bars that are not needed
function remove_some_widgets(){
    unregister_sidebar( 'footer1' );
    unregister_sidebar( 'footer2' );
}
add_action( 'widgets_init', 'remove_some_widgets', 11 );

function excerpt($limit) {
  $excerpt = explode(' ', get_the_excerpt(), $limit);
  if (count($excerpt)>=$limit) {
    array_pop($excerpt);
    $excerpt = implode(" ",$excerpt).'...';
  } else {
    $excerpt = implode(" ",$excerpt);
  } 
  $excerpt = preg_replace('`\[[^\]]*\]`','',$excerpt);
  return $excerpt;
}

function content($limit) {
  $content = explode(' ', get_the_content(), $limit);
  if (count($content)>=$limit) {
    array_pop($content);
    $content = implode(" ",$content).'...';
  } else {
    $content = implode(" ",$content);
  } 
  $content = preg_replace('/\[.+\]/','', $content);
  $content = apply_filters('the_content', $content); 
  $content = str_replace(']]>', ']]&gt;', $content);
  return $content;
}

function custom_excerpt_length( $length ) {
    return 10000;
}
add_filter( 'excerpt_length', 'custom_excerpt_length', 100000 );

function woocommerce_subcategory_thumbnail( $category  ) {
    global $woocommerce;

    $small_thumbnail_size  = apply_filters( 'single_product_small_thumbnail_size', 'shop_catalog' );

    $thumbnail_id  = get_woocommerce_term_meta( $category->term_id, 'thumbnail_id', true  );

    if ( $thumbnail_id ) {
        $image = wp_get_attachment_image_src( $thumbnail_id, $small_thumbnail_size  );
        $image = $image[0];
        echo $image;
    }
}
function storehome_widgets_init() {
    register_sidebar( array(
        'name'          => 'Store Home Bottom',
        'id'            => 'store_home_bottom',
        'before_widget' => '<div>',
        'after_widget'  => '</div>',
        'before_title'  => '<h2>',
        'after_title'   => '</h2>',
    ) );
}
add_action( 'widgets_init', 'storehome_widgets_init' );

function etheme_wc_get_product_labels( $product_id = '' ) {
    global $post, $wpdb,$product;
    $count_labels = 0; 
    $output = '';

    if ( etheme_get_option('sale_icon') ) : 
        if ($product->is_on_sale()) {$count_labels++; 
            $output .= '<span class="label-icon sale-label">'.__( 'Special!', ETHEME_DOMAIN ).'</span>';
        }
    endif; 
    
    if ( etheme_get_option('new_icon') ) : $count_labels++; 
        if(etheme_product_is_new($product_id)) :
            $second_label = ($count_labels > 1) ? 'second_label' : '';
            $output .= '<span class="label-icon new-label '.$second_label.'">'.__( 'New!', ETHEME_DOMAIN ).'</span>';
        endif;
    endif; 
    return $output;
}
function etheme_search_form_modal() {
    ?>
        <div id="searchModal" class="mfp-hide modal-type-1 zoom-anim-dialog" role="search">
            <div class="modal-dialog text-center">
            
                <?php 
                    if(!class_exists('WooCommerce')) {
                        get_search_form();
                    } else {
                        get_template_part('woosearchform'); 
                    }   
                ?>
                
            </div>
        </div>
    <?php
}


