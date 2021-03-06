# ToolWine ShortCodes

Additional ShortCodes for usage with Toolset, WPML and WordPress
Install and activate like any other plugin, **requires** Toolset, WPML is optional.

# Usage
- [DEVELOPERS] discover **existing shortcodes** by calling `[tws_info]` shortcode anywhere. 
  It will produce a simple var_dump with all ShortCode tags available and their callabacks.   
  Callbacks can be used as filters like so: `add_filter('callback_name',$output);` for each shortcode unless the `tws_info`. Example:
  ```
  add_filter( 'tws_get_wp_children', 'custom_tws_get_wp_children', 10, 1);
  function custom_tws_get_wp_children($output){
	  $output = "whatever";
	  return $output;
  }
  ```
  
- [DEVELOPERS] discover **each shortcodes attributes** by calling `[the_shortcode info="1"]` 
  (replace `the_shortcode` with the actual shortcode tag dicovered above). 
  It will produce a simple var_dump with all default attributes and their default value.
  Usefful for easier discovery of possible attributes and also see what values are possible to pass
  
- [USER] call actual shortcodes like any other shortcode in WordPress!

# Current list of ShortCodes (Production):
- `tws_group_by`  Group entries in a Loop by dates
- `tws_get_term`  Display all Term Object properties of a given term, can be set by all known get_term params
- `tws_get_lang`  Display Current WPML language of set post, and set code 
- `tws_children`  Display count or object data of children posts to set post (WP Native Relationship)

# Current list of ShortCodes (Developers):
- `tws_info`      ToolWine Internal ShortCode to output all available ShortCodes
- each TWS ShortCode supports the attribute `info="1"` to dumpt ShortCode information

# Current list of filters
- You can apply a filter to each TWS ShortCode's output, as explained above in DEV section

# Extend ShortCodes list
You can techincally add your own shortcodes to the `TWS_Shortcodes` class with the `tws_shortcodes` filter. 
It allows you to run your shortcodes with TWS ShortCode attributes - even if you do not declare them in your code.

Example:
```
//Register your custom shortcodes with TWS_Shortcodes
add_filter( 'tws_shortcodes', 'my_custom_shortcodes', 10, 1);

//Define your custom shortcodes
function my_custom_shortcodes($shortcodes){
	$shortcodes['my_shortcode_tag'] = 'my_shortcode_callback';	
	return $shortcodes;
}

//ShortCode Callback with 3rd parameter set for  shortcode_atts
function my_shortcode_callback( $atts ) {
	$atts = shortcode_atts( array(
		'foo' => 'something',
		'bar' => 'something else',
	), $atts, 'my_shortcode_tag' );

	return "foo = {$atts['foo']}";
}
```

Now you can use your custom shortcode with `TWS_Shortcodes` shortcode attributes!
Example, below would now output the information for yoru shortcode, even if you did never register an `info` atttribute for it
`[my_shortcode_tag info="1"]`
