<?php
/**
 * The header for our theme.
 *
 * Displays all of the <head> section and everything up till <div id="content">
 *
 * @package Original Flea
 */
	global $current_user;
?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<link rel="shortcut icon" href="<?php echo get_stylesheet_directory_uri(); ?>/favicon.ico" />
<link rel="profile" href="http://gmpg.org/xfn/11" />
<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />

<?php wp_head(); ?>
	<script>
		(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
				(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
			m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
		})(window,document,'script','//www.google-analytics.com/analytics.js','ga');

		ga('create', 'UA-68300203-1', 'auto');
		ga('send', 'pageview');

	</script>
</head>

<body <?php body_class( 'living-flea' ); ?>>
<div id="page" class="hfeed site">

	<div id="header">
		<div id="header-main">
			<div class="wrapper">
				<a id="logo" href="<?php echo home_url(); ?>"><img src="<?php echo get_stylesheet_directory_uri(); ?>/images/living-flea-logo.png" alt="Living Flea" width="2762" height="349" /></a>
				<div id="share-button">
					<?php
					global $shareAPhoto;
					echo $shareAPhoto->initialize( array( 'button_text' => 'Upload' ) );
					?>
				</div>
				<div id="menu">
					<a id="hamburger"><img src="<?php echo get_stylesheet_directory_uri(); ?>/images/hamburger-menu-icon.png" alt="menu" width="128" height="86" /></a>
					<ul id="nav-items">
						<?php if ( is_user_logged_in() ): ?>
							<li class="user-card">
								<div class="avatar"><?php echo get_living_flea_avatar( $current_user->ID, $current_user->user_email, true, '80' ); ?></div>
								<div class="user-links">
									<p>Welcome <?php echo $current_user->display_name; ?></p>
									<a href="<?php echo get_author_posts_url( $current_user->ID ); ?>">View Profile</a> &bull;
									<span class="fb-logout-status"><a class="logout">Logout</a></span>
								</div>
							</li>
						<?php else: ?>
							<li class="nav-item"><a class="login-prompt" data-template="login" data-element="#shaph-page">Login</a></li>
						<?php endif; ?>
						<li class="nav-item"><a href="<?php echo home_url(); ?>/about">About Living Flea</a></li>
					</ul>
				</div>
			</div>
		</div>
		<div id="header-sub">
			<div class="wrapper">
				<p>A place where treasure hunters can share their flea market adventures.</p>
			</div>
		</div>
	</div>

	<div id="content" class="site-content">
