<?php 

class TagReader
{
	private $_tag_delimiter = '';
	private $_path_delimiter = '';
	private $_tag_sets = array();
	private $_tag_list = array();
	private $_tag_counts = array();
	private $_tag_index = array();
	private $_tag_ranks = array();
	private $_tag_rank_limit = 1;
	private $_uncategorized_label = NULL;
	
	public function __construct($tag_sets=array(), $tag_rank_limit=1, $uncategorized_label='Miscellaneous'){
		$this->_tag_delimiter = chr(31);
		$this->_path_delimiter = chr(31);
		if ( is_array($tag_sets) ) { 
			$this->_tag_sets = $tag_sets;
		} else {
			throw new Exception('Error: tag_sets must be an array.');
		}
		if ( is_numeric($tag_rank_limit) ) {
			$this->_tag_rank_limit = $tag_rank_limit;
		} else {
			throw new Exception('Error: tag_rank_limit must be numeric.');
		}
		if ( is_string($uncategorized_label) ) {
			$this->_uncategorized_label = $uncategorized_label;
		} else {
			throw new Exception('Error: uncategorized_label must be a string.');
		}
		$this->_setTagList();
		$this->_setTagCounts();
		$this->_setTagIndex();
		$this->_setTagRanks();
		$this->_setTagPaths();
	}
	
	private function _setTagList(){
		foreach ( $this->_tag_sets as $key => $set ) {
			foreach ( explode($this->_tag_delimiter, $set['delimited_tags']) as $tag ) {
				$this->_tag_list[] = $tag;
			}
		}
		sort($this->_tag_list);
	}
	
	private function _setTagCounts(){
		foreach ( $this->_tag_list as $tag ) {
			if ( ! isset($this->_tag_counts[$tag]) ) {
				$this->_tag_counts[$tag] = 1;
			} else {
				$this->_tag_counts[$tag] = $this->_tag_counts[$tag] + 1;
			}
		}
	}
	
	private function _setTagIndex(){
		arsort($this->_tag_counts);
		foreach ( $this->_tag_counts as $tag => $count ) {
			$this->_tag_index[] = array('tag'=>$tag, 'count'=>$count);
		}
	}
	
	private function _setTagRanks(){
		$rank = 1;
		$next_count = NULL;
		if ( isset($this->_tag_index[(0+1)]['count']) ) {
			$next_count = $this->_tag_index[(0+1)]['count'];
		} elseif( isset($this->_tag_index[0]['count']) ) {
			$next_count = $this->_tag_index[0]['count'];
		}
		if ( $next_count !== NULL ) {
			foreach ( $this->_tag_index as $n => $data ) {
				if ( isset($this->_tag_index[($n+1)]['count']) ) {
					$next_count = $this->_tag_index[($n+1)]['count'];
				}
				$this->_tag_ranks[$data['tag']] = $rank;
				if ( $data['count'] > $next_count  ) {
					$rank++;
				}
			}
		}
	}
	
	private function _setTagPaths(){
		foreach ( $this->_tag_sets as $k => $set ) {
			$temp = array();
			foreach ( explode($this->_tag_delimiter, $set['delimited_tags']) as $tag ) {
				if ( $this->_tag_ranks[$tag] <= $this->_tag_rank_limit ) {
					$temp[$tag] = $this->_tag_ranks[$tag];
				}
			}
			if ( !empty($temp) ) {
				asort($temp);
				$this->_tag_sets[$k]['tag_path'] = implode($this->_path_delimiter, array_keys($temp));
			} else {
				$this->_tag_sets[$k]['tag_path'] = $this->_uncategorized_label;
			}
		}
	}
	
	public function getTagSets(){
		return $this->_tag_sets;
	}
	
	public function getTagList(){
		return $this->_tag_list;
	}
	
	public function getTagCounts(){
		return $this->_tag_counts;
	}
	
	public function getTagIndex(){
		return $this->_tag_index;
	}
	
	public function getTagRanks(){
		return $this->_tag_ranks;
	}
	
	public function getTagRankLimit(){
		return $this->_tag_rank_limit;
	}
}
?>