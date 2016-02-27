<?php
/**
 * Original Flea functions and definitions
 *
 * @package Original Flea
 */

show_admin_bar( false );

/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which
 * runs before the init hook. The init hook is too late for some features, such
 * as indicating support for post thumbnails.
 */
function original_flea_setup() {

	add_theme_support( 'post-thumbnails' );
	// Add default posts and comments RSS feed links to head.
	add_theme_support( 'automatic-feed-links' );

	/*
	 * Let WordPress manage the document title.
	 * By adding theme support, we declare that this theme does not use a
	 * hard-coded <title> tag in the document head, and expect WordPress to
	 * provide it for us.
	 */
	add_theme_support( 'title-tag' );

	/*
	 * Switch default core markup for search form, comment form, and comments
	 * to output valid HTML5.
	 */
	add_theme_support( 'html5', array(
		'search-form', 'comment-form', 'comment-list', 'gallery', 'caption',
	) );

	/*
	 * Enable support for Post Formats.
	 * See http://codex.wordpress.org/Post_Formats
	 */
	add_theme_support( 'post-formats', array(
		'aside', 'image', 'video', 'quote', 'link',
	) );
}
add_action( 'after_setup_theme', 'original_flea_setup' );

/**
 * Enqueue scripts and styles.
 */
function original_flea_scripts() {
	wp_enqueue_style( 'original-flea-style', get_stylesheet_uri() );
}
add_action( 'wp_enqueue_scripts', 'original_flea_scripts' );

/**
 * Load Jetpack compatibility file.
 */
require get_template_directory() . '/inc/jetpack.php';

function get_living_flea_avatar( $id, $email, $link, $size ) {
	$fb_pic = get_user_meta( $id, '_fbpicture', true );
	if ( empty( $fb_pic ) ) {
		$hash = md5( strtolower( trim( $email ) ) );
		$src = "http://www.gravatar.com/avatar/$hash?s=$size";
	} else {
		$src = $fb_pic;
	}
	$avatar = "<img src='$src' alt='' width='$size' height='$size' />";
	if( $link ) {
		$avatar = "<a href='" . get_author_posts_url( $id ) . "'>" . $avatar . "</a>";
	}
	return $avatar;
}

function living_flea_the_avatar() {
	echo get_living_flea_avatar( get_the_author_meta( 'ID' ), get_the_author_meta( 'user_email' ), true, '80' );
}

function the_flea_market_link( $empty_text = 'Living Flea' ) {
	$markets = wp_get_object_terms( get_the_ID(), 'flea-markets' );
	if ( empty( $markets ) ) {
		echo $empty_text;
		return;
	}
	$market = $markets[0];
	echo '<a href="' . home_url() . '/flea-markets/' . $market->slug . '">' . $market->name . '</a>';
}

function the_flea_market_avatar( $market ) {
	$avatar = get_field( 'flea_market_image', $market );
	error_log( print_r( $avatar, true ) );
	if ( $avatar ) {
		$src = $avatar['sizes']['medium'];
		echo "<div class='avatar'><img src='$src' alt='" . esc_attr( $market->name ) . "' /></div>";
	}
}

function the_flea_market_location( $market ) {
	$tst = '123 street st., Portland, ME 04092 USA';
	$address = get_field( 'flea_market_address', $market );
	$city = get_field( 'flea_market_city', $market );
	$region = get_field( 'flea_market_region', $market );
	$postal_code = get_field( 'flea_market_postal_code', $market );
	$country = get_field( 'flea_market_country', $market );

	$location_1 = array();
	if ( ! empty( $address ) ) {
		$location_1[] = $address;
	}

	if ( ! empty( $city ) ) {
		$location_1[] = $city;
	}

	if ( ! empty( $region ) ) {
		$location_1[] = $region;
	}

	$location_1_string = implode( ', ', $location_1 );

	$location_2 = array();

	if ( ! empty( $location_1_string ) ) {
		$location_2[] = $location_1_string;
	}

	if ( ! empty( $postal_code ) ) {
		$location_2[] = $postal_code;
	}

	if ( ! empty( $country ) ) {
		$location_2[] = $country;
	}

	$location_2_string = implode( ' ', $location_2 );

	if ( ! empty( $location_2_string ) ) {
		echo "<p class='flea-market-location'>$location_2_string</p>";
	}

}

function render_living_flea_footer_credits() {
	$credits = '<a href="http://wordpress.org/" rel="generator">Proudly powered by WordPress</a> ';
	$credits .= '&copy; ' . date( 'Y' ) . ' Rocco Tripaldi';
	return $credits;
}


add_filter( 'infinite_scroll_credit', 'render_living_flea_footer_credits' );

function jptweak_remove_share() {
	remove_filter( 'the_content', 'sharing_display',19 );
	remove_filter( 'the_excerpt', 'sharing_display',19 );
}
add_action( 'loop_start', 'jptweak_remove_share' );