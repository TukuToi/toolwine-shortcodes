<?php
class TWS_Shortcodes{

	public $shortcodes;
	public $post;
	
	function __construct($shortcodes) {
		$this->shortcodes = $shortcodes;
		$this->post 	  = $this->tws_current_post();

		$this->tws_register_shortcodes($this->shortcodes);
	}
	
	/**
	 * @atts	condition (year|month),value(DATE)
	 */
	function tws_group_by( $atts, $content = null ) {

		$atts = $this->tws_register_atts( 
			array(
				'condition' => 'year',
				'value' 	=> date('Y'),
			)
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
		       	return $this->tws_return_shortcode($content);
	    	}	      	
	    	break;
		}

		return '';

	}
	
	/**
	 * Get Term by
	 * @return Term Object 
	 */
	function tws_get_term_by( $atts, $content = null ) {

		$atts = $this->tws_register_atts( 
			array(
				'field' 	=> 'term_id',
				'value' 	=> '1', 
				'taxonomy'	=> 'category', 
				'output'	=> 'name', 
				'filter'	=> 'raw'
			)
		);

		$term 	= get_term_by($a['field'], $a['value'], $a['taxonomy'], OBJECT, $a['filter']);

		if(is_object($term) && !empty($term)){
			$prop = $a['output'];
			if( $a['output'] == 'description' && $a['filter'] == 'display' ){
				return tws_return_shortcode($term->$prop);
			}
			return $term->$prop;
		}
		return '';

	}
	
	/**
	 * Get Current WPML Language code 
	 */
	function tws_current_wpml_language( $atts ) {
		
		$atts = $this->tws_register_atts(
			array(
				'id' 	=> $this->post,
				'part' 	=> 'language_code',
			)
		);

		if(function_exists('wpml_get_language_information')){	
			$language = wpml_get_language_information($atts['id']);
			return $language[$atts['part']];
		}
	     
	    return '';

	}
	
	function tws_current_post(){
		global $post;
		return $post;
	}

	function tws_register_atts($atts){
		$dbt 	= debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS,2);
        	$caller = isset($dbt[1]['function']) ? $dbt[1]['function'] : null;
		$atts 	= shortcode_atts( $atts, $atts, array_search($caller, $this->shortcodes) );
		return $atts;
	}
	
	function tws_return_shortcode($content){
		return wpv_do_shortcode($content);
	}

	function tws_register_shortcodes($shortcodes){
		foreach ($shortcodes as $shortcode => $callback) {
			add_shortcode( $shortcode, array( $this, $callback ) );
		}
	}

}
