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

	function tws_return_shortcode($content){
		return wpv_do_shortcode($content);
	}

	function tws_register_shortcodes($shortcodes){
		foreach ($shortcodes as $shortcode => $callback) {
			add_shortcode( $shortcode, array( $this, $callback ) );
		}
	}

}
