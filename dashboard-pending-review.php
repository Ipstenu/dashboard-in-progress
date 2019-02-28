<?php
/*
 * Plugin Name: Dashboard: Pending Review
 * Plugin URI: TBD
 * Description: Displays pending review posts on your dashboard.
 * Version: 3.0.0
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

	// Class initialization
	public function DashboardPendingReview() {
		if ( ! current_user_can( 'edit_posts' ) ) {
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
		wp_register_sidebar_widget( 'dashboard_pending_review', __( 'Posts Pending Review', 'dashboard-pending-review' ), array( &$this, 'widget' ) );
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
		$pendings_query = new WP_Query(
			array(
				'post_type'      => 'post',
				'what_to_show'   => 'posts',
				'post_status'    => 'pending',
				'posts_per_page' => 25,
				'orderby'        => 'ID', // sort by order created, regardless of date
				'order'          => 'DESC',
			)
		);
		$pendings       =& $pendings_query->posts;

		if ( $pendings && is_array( $pendings ) ) {
			$list = array();
			foreach ( $pendings as $pending ) {
				$url   = get_edit_post_link( $pending->ID );
				$title = _draft_or_post_title( $pending->ID );
				// Translators: %s = title.
				$item  = "<h4><a href='$url' title='" . sprintf( __( 'Edit "%s"' ), esc_attr( $title ) ) . "'>$title</a>";
				$item .= "<abbr title='" . get_the_time( __( 'Y/m/d g:i:s A' ), $pending ) . "'>" . get_the_time( get_option( 'date_format' ), $pending ) . '</abbr></h4>';
				if ( $the_content = preg_split( '#\s#', wp_strip_all_tags( $pending->post_content ), 11, PREG_SPLIT_NO_EMPTY ) ) {
					$item .= '<p>' . join( ' ', array_slice( $the_content, 0, 10 ) ) . ( 10 < count( $the_content ) ? '&hellip;' : '' ) . '</p>';
				}
				$list[] = $item;
			}
			?>
	<ul>
		<li><?php echo join( "</li>\n<li>", wp_kses_post( $list ) ); ?></li>
	</ul>
	<p class="textright"><a href="edit.php?post_status=pending" class="button"><?php esc_html_e( 'View all' ); ?></a></p>
			<?php
		} else {
			esc_html_e( 'There are no pending posts at the moment', 'dashboard-scheduled-posts' );
		}
	}
}

// Start this plugin once all other plugins are fully loaded
add_action( 'plugins_loaded', 'dashboard_pending_review_loaded' );
function dashboard_pending_review_loaded() {
	new DashboardPendingReview();
}
