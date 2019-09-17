<?php
/**
 * Hyperdrive Styles
 *
 * @package Hyperdrive
 */

namespace hyperdrive;

class HyperdriveStyles extends HyperdriveFetcher {
	
	
	
	public function __construct() {
		global $wp_styles;
		
		parent::__construct($wp_styles);
		$this->wpInit();
		// add_action('wp_loaded', array($this, 'wpInit'));
		
	}
	
	
	
	public function wpInit() {
		
		add_filter('style_loader_tag', array($this, 'removeStyle'), 999, 2);
		
		add_action('wp_head', array($this, 'legacySupport'), 999);
		
	}
	
	
	
	public function removeStyle($tag, $handle) {
		
		if ($this->isHandleFetchable($handle)) {
			$this->legacy[] = $tag;
			$tag = '<noscript>' . rtrim($tag, "\r\n") . "</noscript>\n";
		}
		
		return $tag;
		
	}
	
	
	
	public function legacySupport() {
		
		echo '<script>if(!window.fetch){' . "\n";
		
		foreach ($this->legacy as $legacy)
			echo "document.write(" . json_encode($legacy) . ");\n";
			
		echo '}</script>';
		
	}
	
	
	
}