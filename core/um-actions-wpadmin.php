<?php

	/***
	***	@redirect wp-admin for non guests
	***/
	add_action('init','um_block_wpadmin_for_guests');
	function um_block_wpadmin_for_guests() {
	global $pagenow;
	
		// Login screen
		if ( isset( $pagenow ) && $pagenow == 'wp-login.php' && !is_user_logged_in() && !isset( $_REQUEST['action'] ) ) {
			
			$allowed = um_get_option('wpadmin_login');
			if ( !$allowed ) {
				
				$act = um_get_option('wpadmin_login_redirect');
				$custom_url = um_get_option('wpadmin_login_redirect_url');
				
				if ( $act == 'um_login_page' || !$custom_url ) {
					$redirect = um_get_core_page('login');
				} else {
					$redirect = $custom_url;
				}
				exit( wp_redirect( $redirect ) );
			}
		}
		
		// Register screen
		if ( isset( $pagenow ) && $pagenow == 'wp-login.php' && !is_user_logged_in() && isset( $_REQUEST['action'] ) && $_REQUEST['action'] == 'register' ) {
			
			$allowed = um_get_option('wpadmin_register');
			if ( !$allowed ) {
				
				$act = um_get_option('wpadmin_register_redirect');
				$custom_url = um_get_option('wpadmin_register_redirect_url');
				
				if ( $act == 'um_register_page' || !$custom_url ) {
					$redirect = um_get_core_page('register');
				} else {
					$redirect = $custom_url;
				}
				exit( wp_redirect( $redirect ) );
			}
		}

	}
	
	/***
	***	@checks if user can access the backend
	***/
	function um_block_wpadmin_by_user_role(){
		global $ultimatemember;
		if( is_admin() && !defined('DOING_AJAX') && um_user('ID') && !um_user('can_access_wpadmin')){
			um_redirect_home();
		}
	}
	add_action('init','um_block_wpadmin_by_user_role', 99);
	
	/***
	***	@hide admin bar appropriately
	***/
	function um_control_admin_bar(){
		if( !is_admin() && !um_user('can_access_wpadmin')) {
			return false;
		} else {
			return true;
		}
	}
	add_filter( 'show_admin_bar' , 'um_control_admin_bar');