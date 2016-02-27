<?php

/*
Plugin Name: Living Flea
Description: Custom Post Types for livingflea.com
Author: Rocco Tripaldi
Version: 1.0
*/

define( 'LIFL_DIR', dirname( __FILE__ ) );
define( 'LIFL_PATH', plugins_url() . '/living-flea/' );

require_once LIFL_DIR . '/inc/class.living-flea-markets.php';
require_once LIFL_DIR . '/inc/class.living-flea.php';
require_once LIFL_DIR . '/inc/class.living-flea-galleries.php';
require_once LIFL_DIR . '/inc/class.living-flea-comments.php';
