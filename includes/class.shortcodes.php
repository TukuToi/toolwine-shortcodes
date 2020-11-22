<?php
class TWS_Shortcodes{

	public $shortcodes;
	
	function __construct($shortcodes) {
		$this->shortcodes = $shortcodes;

		$this->tws_register_shortcodes($this->shortcodes);
	}
	
	/**
	 * @atts	condition (year|month),value(DATE)
	 */
	function tws_group_by( $atts, $content = null ) {

		$a = shortcode_atts( array(
			'condition' => 'year',
			'value' 	=> date('Y'),
		), $atts );

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

		$a = shortcode_atts( array(
			'field' 	=> 'term_id',//'slug', 'name', 'id' or 'ID' (term_id), or 'term_taxonomy_id'.
			'value' 	=> '1',//Search for this term value.
			'taxonomy'	=> 'category',//Taxonomy name. Optional, if $field is 'term_taxonomy_id'.
			'output'	=> 'name',//NOT Native - 'term_id', 'name', 'slug', 'term_group', 'term_taxonomy_id', 'taxonomy', 'description', 'parent', 'count', 'filter'
			'filter'	=> 'raw'//'edit', 'db', 'display', 'attribute', or 'js'.
		), $atts );

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

	function tws_return_shortcode($content){
		return wpv_do_shortcode($content);
	}

	function tws_register_shortcodes($shortcodes){
		foreach ($shortcodes as $shortcode => $callback) {
			add_shortcode( $shortcode, array( $this, $callback ) );
		}
	}

}
