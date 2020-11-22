<?php
class TWS_Shortcodes{

	public $shortcodes;
	public $post;
	public $out;
	
	function __construct($shortcodes) {
		$this->shortcodes = $shortcodes;
		$this->post 	  = $this->tws_current_post();
		$this->out 		  = '';

		$this->tws_register_shortcodes($this->shortcodes);
	}
	
	/**
	 * Group by date
	 * @atts	condition (year|month),value(DATE)
	 */
	function tws_group_by( $atts, $content = null ) {

		$atts = $this->tws_register_atts( 
			array(
				'condition' => 'year',
				'value' 	=> date('Y'),
			), $atts
		);

		static $year 	= null;
  		static $month 	= null;
		$condition 	= $a['condition'];
		$value 		= $a['value'];

		switch ($condition) {
		    case 'year':
		   	case 'month':
		    if ($$condition != $value) {
		       	$$condition = $value;
		       	$this->out = $this->tws_return_shortcode($content);
	    	}	      	
	    	break;
		}

		return apply_filters(__FUNCTION__, $this->out);

	}
	
	/**
	 * Get Term by
	 * @return Term Object 
	 * @atts field(term_id|slug), value, taxonomy(taxonomy name), output(description|all term object properties|archive-url), filter
	 */
	function tws_get_term_by( $atts ) {

		$atts = $this->tws_register_atts( 
			array(
				'field' 	=> 'term_id',
				'value' 	=> '1', 
				'taxonomy'	=> 'category', 
				'output'	=> 'name', 
				'filter'	=> 'raw'
			), $atts
		);

		$term 	= get_term_by($atts['field'], $atts['value'], $atts['taxonomy'], OBJECT, $atts['filter']);

		if(is_object($term) && !empty($term)){
			$prop = $atts['output'];
			if( $atts['output'] == 'description' && $atts['filter'] == 'display' ){
				$this->out = $this->tws_return_shortcode($term->$prop);
			}
			elseif( $atts['output'] == 'archive-url' ){
				$url = get_term_link( $term->$term_id , $term->taxonomy );
				if(!is_wp_error( $url ))
					$this->out = $url;
			}
			$this->out = $term->$prop;
		}

		return apply_filters(__FUNCTION__, $this->out);

	}
	
	/**
	 * Count WP Native childred on post
	 * @atts post_parent(int), post_type(string post name)
	 * @return int
	 */
	function tws_get_wp_children( $atts ){

		$children_comma_list = '';

		$atts = $this->tws_register_atts( 
			array(
				'output' 		=> 'count',
				'prop'			=> 'ID',
				'post_parent'	=> $this->post->ID,
				'post_type'		=> 'page',
			),$atts
		);

		$args = $this->tws_unset($atts, array('output', 'prop'));

		$children = get_children( $args, OBJECT );

		if( $atts['output'] == 'count' ){
			$this->out = count($children);
		}
		elseif( $atts['output'] == 'comma-list' ){
			$this->out = $this->tws_comma_separate($children, $atts['prop']);
		}

		return apply_filters(__FUNCTION__, $this->out);

	}
	
	/**
	 * Get Current WPML Language code 
	 * @atts id(int post id), part(string code part)
	 */
	function tws_current_wpml_language( $atts ) {
		
		$atts = $this->tws_register_atts(
			array(
				'id' 	=> $this->post,
				'part' 	=> 'language_code',
			), $atts
		);

		if(function_exists('wpml_get_language_information')){	
			$language = wpml_get_language_information($atts['id']);
			$this->out = $language[$atts['part']];
		}
	     
	    return apply_filters(__FUNCTION__, $this->out);

	}

	function tws_shortcodes_info( ) {

	    return var_dump($this->shortcodes);

	}

	function tws_comma_separate($array, $prop){
		$values_comma_list = '';
		foreach ($array  as $key => $value) {
			$values_comma_list .= $value->$prop . ',';
		}
		$values_comma_list = rtrim($values_comma_list, ',');
		return $values_comma_list;
	}

	function tws_unset( $original, $remove ) {
		foreach($remove as $key) {
			unset($original[$key]);
		}
		return $original;
	}
	
	function tws_current_post(){
		global $post;

		return $post;
	}

	function tws_register_atts($default, $atts){
		$dbt 			= debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS,2);
        $caller 		= isset($dbt[1]['function']) ? $dbt[1]['function'] : null;
        if( isset($atts['info']) )
        	$atts['info'] = var_dump($default);
		$atts 	= shortcode_atts( $default, $atts, array_search($caller, $this->shortcodes) );
		return $atts;
	}
	
	function tws_register_shortcodes($shortcodes){
		foreach ($shortcodes as $shortcode => $callback) {
			add_shortcode( $shortcode, array( $this, $callback ) );
		}
	}

	function tws_return_shortcode($content){
		return wpv_do_shortcode($content);
	}

}
