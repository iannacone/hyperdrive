<?php
/**
 * Hyperdrive Fetcher
 *
 * @package Hyperdrive
 */

namespace hyperdrive;

class HyperdriveFetcher {
	
	
	
	public $data = [];
	public $free = [];
	public $legacy = [];
	
	private $site_url;
	private $regex;
	
	
	
	public function __construct($data) {
		
		$this->data = $data;
		$this->site_url = site_url('/');
		$this->regex = '%^(/\w|' . preg_quote($this->site_url) . ')%';
		
	}
	
	
	
	function getDataSrc($data) {
		
		return $data->src . ($data->src && $data->ver ? '?ver=' . $data->ver : '');
		
	}
	
	
	
	function isHandleFetchable($handle) {
		
		return $this->isDataFetchable($this->data->registered[$handle]);
		
	}
	
	
	
	function isDataFetchable($data) {
		
		return
			// exclude element using conditional comments
			(!isset($data->extra['conditional']) || !$data->extra['conditional'])
			// check the CORS
			&& preg_match($this->regex, $data->src);
		
	}
	
	
	
	// prepend the base url of the site
	function getBasedUrl($url) {
		
		if (!preg_match('/^https?\:\/\/.*$/i', $url) && strlen($url) > 0) {
			$url = $this->site_url . ltrim($url, '/');
		}
		
		return $url;
		
	}
	
	
	
	function getHandleDependencies($handles) {
		
		$dependency_data = null;
		
		foreach ($handles as $handle) {
			
			$data = $this->data->registered[$handle];
			$dependencies = $this->getHandleDependencies($data->deps);
			$src = $this->getDataSrc($data);
			$src = $this->getBasedUrl($src);
			
			if ($src)
				$dependency_data[$src] = $dependencies;
			else // no src. for ex. jquery
				$dependency_data = $dependencies;
			
		}
		
		return $dependency_data;
		
	}
	
	
	
	function getStructure() {
		
		$structure = [];
		
		foreach ($this->data->queue as $handle) {
			
			$data = $this->data->registered[$handle];
			
			if ($this->isDataFetchable($data)) {
				$dependencies = $this->getHandleDependencies($data->deps);
				$src = $this->getDataSrc($data);
				$src = $this->getBasedUrl($src);
				
				if ($dependencies)
					$structure[$src] = $dependencies;
				else // they are not dependents
					$this->free[] = $src;
			}
			
		}
		
		return $structure;
		
	}
	
	
	
	private function getLeveledStructureRecursive($structure, &$levels, $level) {
		
		$sub_level = $level + 1;
		
		if ($structure)
			foreach ($structure as $handle => $dependencies) {
				
				if ($dependencies)
					$this->getLeveledStructureRecursive($dependencies, $levels, $sub_level);
				
				// if there is a higher level, overwrite the lower one
				if (!isset($levels[$handle]) || $levels[$handle] < $level)
					$levels[$handle] = $level;
				
			}
		
	}
	
	
	
	function getLeveledStructure($structure) {
		
		$leveled = [];
		
		$this->getLeveledStructureRecursive($structure, $leveled, 0);
		arsort($leveled);
		
		$data = array_keys($leveled);
		// array_values avoids the string numeric indexes on some servers
		// array_filter avoids empty values from the array_diff result on some servers
		$this->free = array_values(array_filter(array_diff($this->free, $data)));
		// $this->legacy = array_merge($this->free, $data);
		
		return $leveled;
		
	}
	
	
	
	function getFetchesStructure($structure) {
		
		$fetches = '';
		
		if ($structure) {
			
			$fetches .= 'fetchInject(["' . key($structure) . '"])';
			$current_level = array_shift($structure) - 1;
			// one cycle more
			$structure[''] = -1;
			$dependencies = '';
			
			foreach ($structure as $src => $level) {
				
				if ($current_level !== $level) {
					
					$current_level = $level;
					$fetches = 'fetchInject([' . rtrim($dependencies, ',') . '],' . $fetches . ')';
					$dependencies = '';
					
				}
				
				$dependencies .= '"' . $src . '",';
				
			}
			
			$fetches .= ';';
			
		}
		
		if ($this->free)
			$fetches .= "fetchInject(" . json_encode($this->free, JSON_UNESCAPED_SLASHES) . ");";
		
		return $fetches;
		
	}
	
	
	
	public function getFetches() {
		$structure = $this->getStructure();
		$leveled = $this->getLeveledStructure($structure);
		$fetches = $this->getFetchesStructure($leveled);
		return $fetches;
	}
	
	
	
}