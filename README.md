# toolwine-shortcodes
Additional ShortCodes for usage with Toolset and WordPress

# Usage
- [DEVELOPERS] discover existing plugins by calling `[tws_info]` shortcode anywhere. It will produce a simple var_dump with all ShortCode tags available and their callabacks
- [DEVELOPERS] discover each shortcode attributes by calling `[the_shortcode info]` (replace `the_shortcode` with the actual shortcode tag dicovered above). It will produce a simple var_dump with all default attributes and their default values, for easier discovery of possible attributes and also see what values are possible to pass
- [USER] call actual shortcodes like any other shortcode in WordPress.

# Current list of ShortCodes:
- `tws_group_by`  Group entries in a Loop by dates
- `tws_get_term`  Display all Term Object properties of a given term, can be set by all known get_term params
- `tws_get_lang`  Display Current WPML language of set post, and set code 
- `tws_children`  Display count or object data of children posts to set post (WP Native Relationship)
- `tws_info`      ToolWine Internal ShortCode to output all available ShortCodes
