<?php
/*
Plugin Name: Admin Bar Menu Packer
Plugin URI:  https://wordpress.org/plugins/admin-bar-menu-packer
Description: Gather menu items on toolbar (admin bar) into one menu to prevent toolbar from getting cluttered.
Version:     0.7.4
Author:      Kazuyuki Kumai
Author URI:  https://profiles.wordpress.org/kazuyk/
Text Domain: admin-bar-menu-packer
Domain Path: /languages/
License:     GPL2
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Copyright 2017 Kazuyuki Kumai (email : kazuyk009@hotmail.com)

  This program is free software; you can redistribute it and/or modify
  it under the terms of the GNU General Public License, version 2, as
  published by the Free Software Foundation.

  This program is distributed in the hope that it will be useful,
  but WITHOUT ANY WARRANTY; without even the implied warranty of
  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
  GNU General Public License for more details.

  You should have received a copy of the GNU General Public License
  along with this program; if not, write to the Free Software
  Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

defined( 'ABSPATH' ) or die( 'Don\'t call me directly.' );

define( 'ABMP_NAME', esc_html__( 'Admin Bar Menu Packer', 'admin-bar-menu-packer' ) );


add_action( 'plugins_loaded', 'abmp_plugin_load_textdomain' );

function abmp_plugin_load_textdomain() {
	load_plugin_textdomain( 'admin-bar-menu-packer', false, basename( dirname( __FILE__ ) ) . '/languages' );
}


add_action( 'admin_bar_init', 'abmp_admin_enqueue_scripts' );

function abmp_admin_enqueue_scripts() {
	wp_enqueue_style( 'adminbar-menu-packer-admin',
		plugins_url( 'css/style.css', __FILE__ ),
		array(), '0.7.4', 'all' );
}


add_action( 'wp_before_admin_bar_render', 'abmp_show_menu' );

function abmp_show_menu() {
	global $wp_admin_bar;
	
	if ( ! is_object( $wp_admin_bar ) || ! is_admin_bar_showing() ) {
		return;
	}
	
	$node_count = $packed = 0;
	
	// built-in nodes to be excluded
	$excluding_ids = array( 'menu-toggle','wp-logo','wp-logo-external','site-name','updates','comments','top-secondary','my-account','user-actions' );
	
	// additional nodes to be excluded
	array_push( $excluding_ids, 'show_template_file_name_on_top','query-monitor','backwpup','veu_disable_admin_edit','editGuide' );
	
	$all_nodes = $wp_admin_bar->get_nodes();
	
	if ( $all_nodes ) {
	
		if ( WP_DEBUG ) {
			$node_meta = array( 'title' => esc_html__( 'ABMP debug mode', 'admin-bar-menu-packer' ) );
		} else {
			$node_meta = '';
		}
		$wp_admin_bar->add_node( array(
				'id'    => 'abmp-root',
				'title' => '<span class="ab-icon"></span><span class="ab-label"></span>',
				'meta'  => $node_meta,
			) );
		
		foreach ( $all_nodes as $node ) {
			
			$node_count += 1;
			
			if ( in_array( $node->id, $excluding_ids, true ) ) {
				
				continue;
				
			} else if ( in_array( $node->parent, $excluding_ids, true ) ) {
				
				$excluding_ids[] = $node->id;
				
			} else {
				
				if ( !$node->parent ) {
					$wp_admin_bar->remove_node( $node->id );
				}
				
				if ( WP_DEBUG ) {
					
					$node_meta = array(
								'class' => 'abmp-item',
								'title' => $node_count . ' ' . $node->id 
							);
					
				} else {
					
					$node_meta = array( 'class' => 'abmp-item' );
					
				}
				
				$args = array(
							'id'    => 'abmp-' . $node->id,
							'title' => strip_tags( $node->title ),
							'href'  => $node->href,
							'meta'  => $node_meta,
						);
				
				if ( isset( $node->parent ) && $node->parent 
						&& $node->parent !== 'top-secondary' ) {
					$args['parent'] = 'abmp-' . $node->parent;
				} else {
					$args['parent'] = 'abmp-root';
				}
				$wp_admin_bar->add_node( $args );
				$packed += 1;
			}
		}
		
		if ( $packed == 0 ) {
			$args = array(
						'id'     => 'abmp-no-item',
						// translators: label when the menu is empty
						'title'  => '(' . strip_tags( __( 'no items', 'admin-bar-menu-packer' ) ) .')',
						'parent' => 'abmp-root',
					);
			$wp_admin_bar->add_node( $args );
		}
		abmp_hide_howdy( true );
		if ( WP_DEBUG ) abmp_debug_menu( $all_nodes );
	}
}


add_action( 'admin_notices', 'abmp_admin_notices' );

function abmp_admin_notices() {
	
	if ( WP_DEBUG ) {
	
		// translators: %s is plugin name
		$debug_notice1 = sprintf( esc_html__( '%1$s is currently working in debug mode, because WP_DEBUG is set to true.', 'admin-bar-menu-packer' ), ABMP_NAME );
		// translators: %s is the label for debug menu ('All Nodes ID')
		$debug_notice2 = sprintf( esc_html__( 'Each item in the menu has a title attribute including the node ID, and a debug menu %s containing all items\' node ID is displayed.', 'admin-bar-menu-packer' ), '\'All Nodes\'' );
		$class = 'notice notice-warning is-dismissible';
		printf( '<div class="%1$s"><p>%2$s<br />%3$s</p></div>', 
				esc_attr( $class ), $debug_notice1, $debug_notice2
			);
	}
}


/**
 * Update 'my-account' node to hide Howdy text
 *
 * @since    0.5.1
 *
 * @param boolean $hide
 */
function abmp_hide_howdy( $hide = true ) {

	global $wp_admin_bar;
	$user_id = get_current_user_id();
	
	if ( $hide ) {
		$avatar = get_avatar( $user_id, 26 );
		$wp_admin_bar->add_node( array(
			'id' => 'my-account',
			'title' => $avatar,
			)
		);
	}
}


/**
 * Show hierarchical menu with all nodes (for debug)
 *
 * @since    0.6.0
 *
 * @param array $nodes
 */
function abmp_debug_menu( $nodes ) {
	
	global $wp_admin_bar;
	
	$args = array(
			'id'     => 'abmp-debug-root',
			'title'  => 'All Nodes',
			'parent' => 'abmp-root',
			'meta'   => array(
							'title' => esc_html__( 'ABMP Debug Menu', 'admin-bar-menu-packer' ),
						),
		);
	$wp_admin_bar->add_node( $args );
	
	$counter = 0;
	
	foreach ( $nodes as $node ) {
		
		$counter += 1;
		$args = array(
				'id'    => 'node_id_' . $node->id,
				'title' => $counter . ' ' . $node->id,
				'href'  => $node->href,
			);
		if ( isset( $node->parent ) && $node->parent ) {
			$args['parent'] = 'node_id_' . $node->parent;
		} else {
			$args['parent'] = 'abmp-debug-root';
		}
		
		$wp_admin_bar->add_node($args);
	}
}


