<?php
add_action('init','fbtab_css');
function fbtab_css() {
if(empty($_GET["fbtab_css"]))
	return;

// load wordpress into external file
// buffer to avoid display of session error
ob_start();

if(isset($_GET["postid"]))
{
$id = $_GET["postid"];
$custom_fields = get_post_custom($id);
}

$width = (!empty($custom_fields["_canvas"][0]) || !empty($_GET["canvas"])) ? 'max-width:710px;' : 'max-width:510px;';

$ob = ob_get_clean();
if(isset($_GET["show"]) && ($_GET["show"] == 'text'))
	header('Content-type: text/plain');
else
	header('Content-type: text/css');
echo $ob;
$fbt_theme = get_option('fbt_theme');
$style =($fbt_theme) ? WP_CONTENT_DIR.'/themes/'.$fbt_theme.'/style.css' : WP_PLUGIN_DIR.'/facebook-tab-manager/fbtab-theme/style.css';
if(isset($_GET["show"]) && $_GET["show"] == 'text')
	echo "/* styles from $style */\n\n";
else
	echo "html .mceContentBody, #content {width: ".$width.";}\n";
include $style;
echo "\n".$custom_fields["_style"][0];
exit();
}
?>