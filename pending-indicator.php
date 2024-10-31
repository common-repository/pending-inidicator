<?php
/*
Plugin Name: Pending Indicator
Plugin URI: http://wordpress.org/plugins/pending-inidicator/
Description: Show the number of pending posts waiting for approval in the admin menu, if any. Also automatically supports custom post types.
Version: 1.1
Author: Kenth Hagström
Author URI: http://kenthhagstrom.se
License: GPL v3

Pending Indicator
Copyright (C) 2013 Kenth Hagström <info@kenthhagstrom.se>

This program is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.
   
You should have received a copy of the GNU General Public License
along with this program. If not, see <http://www.gnu.org/licenses/>.
*/

// Direct access to this file is not allowed
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Show the number of pending posts waiting for approval in the admin menu, if any
 *
 * @version 1.1
 * @since 131218
 * @param array $menu 
 * @return array
 */
function keha_pending_posts_indicator( $menu ) {

	$post_types = get_post_types();
	if ( empty( $post_types ) ) {
		return;
	}
	
	foreach ( $post_types as $type ) {

		$status        = 'pending';
	    $num_posts     = wp_count_posts( $type, 'readable' );
	    $pending_count = 0;

	    if ( ! empty( $num_posts->$status ) ) {
			$pending_count = $num_posts->$status;
		}

	    // Build string to match in $menu array
		if ( $type == 'post' ) {
			$menu_str = 'edit.php';
	    } else {
			$menu_str = 'edit.php?post_type='.$type;
		}

	    // Loop through $menu items, find match, add indicator
	    foreach ( $menu as $menu_key => $menu_data ) {
			if ( $menu_str != $menu_data[ 2 ] ) {
				continue;
			} else {
				// NOTE: Using the same CSS classes as the plugin updates count, it will match your admin color theme just fine.
				$menu[ $menu_key ][0] .= " <span class='update-plugins count-$pending_count'><span class='plugin-count'>" . number_format_i18n( $pending_count ) . '</span></span>';
			}
		}
	}
	return $menu;
}
add_filter( 'add_menu_classes', 'keha_pending_posts_indicator' );