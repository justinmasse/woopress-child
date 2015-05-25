<?php 
define('CHILD_DIR', get_stylesheet_directory());
define('ETHEME_CHILD_CODE_DIR', trailingslashit(CHILD_DIR).'framework');


require_once( trailingslashit(ETHEME_CHILD_CODE_DIR). 'shortcodes.php' );

require_once( trailingslashit(ETHEME_CHILD_CODE_DIR). 'recipe.php' );
require_once( trailingslashit(ETHEME_CHILD_CODE_DIR). 'story.php' );
require_once( trailingslashit(ETHEME_CHILD_CODE_DIR). 'explore.php');
require_once( trailingslashit(ETHEME_CHILD_CODE_DIR). 'press.php' );