<?php
/**
 * Hyperdrive Scripts
 *
 * @package Hyperdrive
 */

namespace hyperdrive;

class HyperdriveScripts extends HyperdriveFetcher {
	
	
	
	public function __construct() {
		global $wp_scripts;
		
		parent::__construct($wp_scripts);
		
		/* resolve a problem in wp for which in the jquery migrate the jquery was not found
		 * because it was loaded in the same jquery deps
		 * 
		 * BEFORE:
		 * 	jquery => [
		 * 		jquery-migrate,
		 * 		jquery-core,
		 * 	]
		 * 
		 * AFTER:
		 * 	jquery => [
		 * 		jquery-migrate => [
		 * 			jquery-core
		 * 		]
		 * 	]
		 */
		
		if ($this->data->registered['jquery-core']) {
			
			if ($this->data->registered['jquery-migrate']) {
				$this->data->registered['jquery-migrate']->deps = ['jquery-core'];
			
				if ($this->data->registered['jquery'])
					$this->data->registered['jquery']->deps = ['jquery-migrate'];
			
			}
			
		}
		
		$this->wpInit();
		
	}
	
	
	
	function wpInit() {
		
		add_filter('script_loader_tag', array($this, 'removeScript'), 999, 2);
		
		add_action('wp_footer', array($this, 'legacySupport'), 1);
		
	}
	
	
	
	public function removeScript($tag, $handle) {
		
		if ($this->isHandleFetchable($handle)) {
			$this->legacy[] = $tag;
			$tag = '';
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