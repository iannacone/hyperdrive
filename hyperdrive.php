<?php
/**
 * Putting WordPress into Hyperdrive.
 *
 * @package     Hyperdrive
 * @author      Simone Iannacone and Josh Habdas and contributors
 * @link        https://wordpress.stackexchange.com/a/263733/117731
 * @license     GPL-3.0 or later
 *
 * @wordpress-plugin
 * Plugin Name: Hyperdriver
 * Plugin URI:  https://github.com/wp-id/hyperdrive
 * Description: The fastest way to load pages in WordPress.
 * Version:     1.0.1-beta
 * Author:      Simone Iannacone and Josh Habdas and contributors
 * License:     GPL-3.0 or later
 * Text Domain: hyperdriver
 * Domain Path: /languages/
 *
 * Hyperdrive. The fastest way to load pages in WordPress.
 * Copyright (C) 2017  Josh Habdas and contributors
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful, but
 * WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU
 * General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, see
 * <https://opensource.org/licenses/GPL-3.0>.
 *
 */


namespace hyperdrive;

define('HYPERDRIVE_ABS', dirname(__FILE__));
define('HYPERDRIVE_STYLES', true);



if (is_admin()) {
	
	
	
	function text_domain() {
		
		load_plugin_textdomain('hyperdrive', false, HYPERDRIVE_ABS . '/languages/');
		
	}
	add_action('plugins_loaded', __NAMESPACE__ . '\text_domain');
	
	require_once(HYPERDRIVE_ABS . '/vendor/wordpress-settings-api-class/class.settings-api.php');
	require_once(HYPERDRIVE_ABS . '/includes/admin/settings.php');
	new WeDevs_Settings();
	return;
	
	
}



$fetch_styles = get_option('wedevs_basics');
$fetch_styles = isset($fetch_styles['styles']) && $fetch_styles['styles'] === 'on';



require_once(HYPERDRIVE_ABS . '/includes/class-fetcher.php');
require_once(HYPERDRIVE_ABS . '/includes/class-scripts.php');

if ($fetch_styles)
	require_once(HYPERDRIVE_ABS . '/includes/class-styles.php');



function head() {
	global $fetch_styles;
	
	$fetches = [];
	$scripts_class = new HyperdriveScripts();
	$fetches[] = $scripts_class->getFetches();

	if ($fetch_styles) {
		$styles_class = new HyperdriveStyles();
		$fetches[] = $styles_class->getFetches();
	}
	
	echo '<script>if(window.fetch){' . "\n";
	include(HYPERDRIVE_ABS . '/vendor/fetch-inject/dist/fetch-inject.min.js');
	echo "\n";
	
	foreach ($fetches as $fetch)
		echo "$fetch\n";
		
	echo '}</script>';
	
}
add_action('wp_head', __NAMESPACE__ . '\head', 1);