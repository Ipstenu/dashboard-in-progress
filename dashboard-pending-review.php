<?php
/*
 * Plugin Name: Dashboard: Pending Review
 * Plugin URI: https://wordpress.org/plugins/dashboard-pending-review/
 * Description: Displays pending review posts on your dashboard.
 * Version: 3.0
 * Author: Viper007Bond, Ipstenu
 * Author URI: https://halfelf.org
 * License: GPLv2 (or Later)
 *
 * Copyright 2008-19 Alex Mills (Viper007Bond) - http://www.viper007bond.com/wordpress-plugins/dashboard-pending-review/
 * Copyright 2019 Mika Epstein (Ipstenu)
 *
 * This file is part of Dashboard: Pending Review, a plugin for WordPress.
 *
 * This plugin is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 2 of the License, or
 * (at your option) any later version.
 *
 * This plugin is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with WordPress.  If not, see <http://www.gnu.org/licenses/>.
 */

class DashboardPendingReview {

	/**
	 * Version Number
	 * @var string
	 */
	public static $version = '3.0';

	/**
	 * __construct function.
	 */
	public function __construct() {
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}

		add_action( 'admin_enqueue_scripts', array( &$this, 'custom_css' ) );
		add_action( 'wp_dashboard_setup', array( &$this, 'register_widget' ) );
		add_filter( 'wp_dashboard_widgets', array( &$this, 'add_widget' ) );
	}


	/**
	 * Register this widget
	 * we use a hook/function to make the widget a dashboard-only widget
	 */
	public function register_widget() {
		wp_add_dashboard_widget( 'dashboard_pending_review', __( 'Pending Review', 'dashboard-pending-review' ), array( &$this, 'widget' ) );
	}

	/**
	 * Output custom CSS
	 */
	public function custom_css() {
		if ( is_user_logged_in() && is_admin() ) {
			wp_register_style( 'dashboard_pending_review', plugins_url( 'style.css', __FILE__ ), false, self::$version );
			wp_enqueue_style( 'dashboard_pending_review' );
		}
	}

	/**
	 * Modifies the array of dashboard widgets and adds this plugin's
	 */
	public function add_widget( $widgets ) {
		global $wp_registered_widgets;

		if ( isset( $wp_registered_widgets['dashboard_pending_review'] ) ) {
			$widgets[] = 'dashboard_pending_review';
		}

		return $widgets;
	}

	/**
	 * Output the widget contents
	 */
	public function widget( $args ) {
		$filtered_post_type  = apply_filters( 'dashboard_pending_review_post_type', 'post' );
		$filtered_post_type  = ( post_type_exists( $filtered_post_type ) || 'any' === $filtered_post_type ) ? $filtered_post_type : 'post';
		$post_type_object    = get_post_type_object( $filtered_post_type );
		$post_type_name      = $post_type_object->labels->name;
		$pendings_query      = new WP_Query(
			array(
				'post_type'      => $filtered_post_type,
				'what_to_show'   => 'posts',
				'post_status'    => 'pending',
				'posts_per_page' => absint( apply_filters( 'dashboard_pending_review_posts_shown', 5 ) ),
				'orderby'        => 'ID', // sort by order created, regardless of date
				'order'          => 'DESC',
			)
		);
		$pending_posts_array =& $pendings_query->posts;

		echo '<div class="pending">';

		if ( $pending_posts_array && is_array( $pending_posts_array ) ) {
			$list = array();

			foreach ( $pending_posts_array as $pending ) {
				// Default Data
				$url    = get_edit_post_link( $pending->ID );
				$title  = _draft_or_post_title( $pending->ID );
				$author = get_the_author_meta( 'display_name', $pending->post_author );

				// Translators: %s = title.
				$item  = '<div class="pending-title"><a href="' . esc_url( $url ) . '" aria-label="' . esc_attr( sprintf( __( 'Edit &#8220;%s&#8221;' ), $title ) ) . '">' . esc_html( $title ) . '</a>';
				$item .= '<time datetime="' . get_the_time( 'c', $pending ) . '">' . get_the_time( __( 'F j, Y' ), $pending ) . '</time>';
				// Translators: %s = author display name
				$item .= '<span class="author">(' . sprintf( __( 'By %s', 'dashboard-pending-review' ), $author ) . ')</span></div>';

				// Content if applicable
				$the_content = wp_trim_words( $pending->post_content, 10 );
				if ( $the_content ) {
					$item .= '<p>' . $the_content . '</p>';
				}
				$list[] = $item;
			}
			?>

			<p class="view-all"><a href="<?php echo esc_url( admin_url( 'edit.php?post_status=pending' ) ); ?>">
				<?php
				// Translators: %s = post type (i.e. posts, pages, etc)
				echo esc_html( sprintf( __( 'View all pending %s', 'dashboard-pending-review' ), lcfirst( $post_type_name ) ) );
				?>
			</a></p>

			<ul>
				<li><?php echo join( "</li>\n<li>", wp_kses_post( $list ) ); ?></li>
			</ul>

			<?php
		} else {
			// Translators: %s = post type (i.e. posts, pages, etc)
			echo esc_html( sprintf( __( 'There are no pending %s at this time.', 'dashboard-pending-review' ), lcfirst( $post_type_name ) ) );
		}

		echo '</div>';
	}
}

// Start this plugin once all other plugins are fully loaded
add_action( 'plugins_loaded', 'dashboard_pending_review_loaded' );
function dashboard_pending_review_loaded() {
	new DashboardPendingReview();
}
