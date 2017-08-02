<?php
/*
Plugin Name: Facebook Tab Manager
Plugin URI: http://facebooktabmanager.com
Description: Makes WordPress function as an editor for tabs you can embed in a Facebook page for your business, campaign, or organization. Include any WordPress content, including output from Shortcodes and other plugin functions.
Author: David F. Carr
Version: 3.4.4
Author URI: http://www.carrcommunications.com/
*/

//make sure new rules will be generated for custom post type
include 'fbtab-css.php';

function is_fbtab() {
global $wp;
	if ((isset($wp->query_vars["post_type"]) && $wp->query_vars["post_type"] == "fbtab") || ( isset($_GET["fb"]) && ($_GET["fb"] == 'tab')) || isset($_GET["fbtab"]) || strpos($_SERVER['REQUEST_URI'],'fbtab/') )
		return true;
	else
		return false;
}

function draw_taboptions() {

global $wpdb;
global $post;

if(isset($_GET["post"]) )
{
$custom_fields = get_post_custom($post->ID);
$permalink = get_permalink($post->ID);
}
else
{
	$fbtabset = get_option('fbtabset');
	$remove_head = get_option('fbt_remove_head');
	$remove_footer = get_option('fbt_remove_footer');	
	$remove_filter = get_option('fbt_remove_filter');
	if($fbtabset)
	foreach ($fbtabset as $name => $value)
		$custom_fields['_'.$name][0] = $value;
	
	if($remove_filter)
		$custom_fields["_remove_filter"] = $remove_filter;
	if($remove_head)
		$custom_fields["_remove_head"] = $remove_head;
	if($remove_footer)
		$custom_fields["_remove_footer"] = $remove_footer;

}

$templates = get_fbtab_templates();
if($templates)
foreach($templates as $name => $value)
	{
		$selected = ($value == $custom_fields["_wp_page_template"][0]) ? ' selected="selected" ' : '';
		$template_options .= sprintf('<option value="%s" %s>%s</option>',$value,$selected,$name);
	}
?>
<p>Template: <select name="fbtset[wp_page_template]"><option value="">Default</option><?php echo $template_options; ?></select></p>
<p>Tab Layout: <select name="fbtset[layout]"><option value="timeline">Timeline (810-pixel)</option><option value="520" <?php if( !strpos($_SERVER['REQUEST_URI'],'new') && (!$custom_fields["_layout"][0] || ($custom_fields["_layout"][0] == 520))  ) echo ' selected="selected" '; ?> >520-pixels</option></select></p>

<?php

fbtab_options_ui($custom_fields);

?>

<input type="hidden" name="fbtabdata" value="1"  />
<?php

if($post->post_status != 'publish')
	echo "<h3>After you publish your post, instructions for registering your content on Facebook will appear below.</h3>";
elseif($permalink)
	{
	
if($post->post_parent)
	{
	$canvas = get_permalink($post->post_parent);
	$parent = $post->post_parent;
	}
else
	{
	$child = $wpdb->get_var("SELECT ID from $wpdb->posts WHERE post_parent=$post->ID AND post_status='publish'");
	if($child)
		{
		$canvas = $permalink;
		$tab = get_permalink($child);
		}
	else
		{
		$canvas = $permalink;
		$canvas .= (strpos($permalink,'?') ) ? '&' : "?";
		$canvas .= 'canvas=1';
		$tab = $permalink;
		}
	}
	$scanvas = str_replace('http:','https:',$canvas);
	$stab = str_replace('http:','https:',$tab);

?>
<h3  id="tabinstall">To Install This as a Facebook Tab</h3>
<ol>
  <li>Visit the <a href="https://developers.facebook.com/apps" target="_blank">Facebook Developers</a> utility for app registration</li>
  <li>Select Create New App from the Menu</li>
  <li>Fill out the registraition form, including the parameters below.
  
<p>Tab Name: (enter a short label for the tab)<br />
Tab URL: <strong><?php echo $tab; ?></strong><br />
Secure Tab URL: <strong><?php echo $stab; ?></strong></p>
<p>Optional - <em>used to make your content appear as a stand-alone app rather than a page tab</em><br />
Canvas URL: <strong><?php echo $canvas; ?></strong><br />
Secure Canvas URL: <strong><?php echo $scanvas; ?></strong></p>
  </li>
  <li>Record the App ID # assigned by Facebook here: <input type="text" name="fbtset[appid]" value="<?php echo $custom_fields["_appid"][0];?>" /><input type="submit" value="Save" />
  </li>
  <li>
  <?php 
  if(!empty($custom_fields["_appid"][0]))
	echo sprintf('<a target="_blank" href="https://www.facebook.com/dialog/pagetab?app_id=%s
&redirect_uri=%s">Add to Page</a>  - click to add your tab to one or more pages.',$custom_fields["_appid"][0],urlencode('https://www.facebook.com') );//&display=popup
  else
  	echo "ADD TO PAGE LINK WILL BE DISPLAYED HERE (you must record App ID first)";?>
  </li>
</ol>

<p><strong style="color: #FF0000;">Note:</strong> You must obtain and install an SSL security certificate for your domain before you register secure URLs with Facebook. Secure URLs help ensure your content is displayed properly for people using Facebook's "secure browsing" (https encryption) feature and is a Facebook requirement.</p>
<p>After entering the URLs above into the Facebook Developers Utility, return to this screen to record the App ID # assigned by Facebook. At that point, Facebook Tab Manager will display an Add to Page link you can use to add your tab to one or more Facebook pages. More documentation at the <a href="http://tabmgr.com">Facebook Tab Manager home page</a>.</p>

<p>Example:</p>
<ol>
<li><strong>Create a New App</strong> in the <a href="https://developers.facebook.com/apps" target="_blank">Facebook Developers</a> utility. On the Settings screen, you will click <strong>Add Platform</strong> and select <strong>Page Tab</strong>.
<p><img src="<?php echo plugins_url('facebook-tab-manager/screenshot-3.png'); ?>" width="549" height="459" alt="Create an App" /></p>
</li>
<li>Provide a name for the tab and copy-and-paste in the url paramaters obtained from Facebook Tab Manager. The Settings screen also includes the <strong>App ID</strong> that you will want to copy-and-paste back into Facebook Tab Manager.
<p><img src="<?php echo plugins_url('facebook-tab-manager/screenshot-4.png'); ?>" width="600" height="529" alt="Enter Tab Parameters" /></p>
</li>
<li>Your app will initially be in "sandbox mode" (visible only to you). Go to the Status &amp; Review screen in the Facebook developers app and toggle the button for making your app live from No to Yes. Confirm that you really want to make it live. You should be able to ignore the Submit Items for Approval option unless you've done something particularly fancy, beyond the functions Facebook Tab Manager itself provides.
<p><img src="<?php echo plugins_url('facebook-tab-manager/screenshot-5.png'); ?>" width="600" height="262" alt="Activate App" /></p>
</li>
<li>Once you have recorded the App ID provided by Facebook in Facebook Tab Manager you will see an <strong>Add to Page</strong> link appear within the Facebook Tab Manager user interface. Clicking that link will now take you to a screen on the Facebook website where you will be able to specify a page you want the tab added to.
<p><img src="<?php echo plugins_url('facebook-tab-manager/screenshot-6.png'); ?>" width="566" height="227" alt="Add to Page" /></p>
</li>
</ol>
<?php
	}

}

// create custom plugin settings menu
add_action('admin_menu', 'fbtab_create_menu');

function fbtab_create_menu() {

	//create new top-level menu
	add_submenu_page('options-general.php','FB Tab Settings', 'FB Tab Settings', 'manage_options', 'fbtab_settings_page', 'fbtab_settings_page');

}

//modify edit columns for fbtabs
add_action( 'admin_init', 'fbtab_edit_menu_list' );

function fbtab_edit_menu_list() {
add_filter('manage_edit-fbtab_columns', 'fbtab_add_post_columns');
add_action('manage_pages_custom_column', 'fbtab_render_post_columns', 10, 2);
}

function fbtab_add_post_columns($columns) {
	unset($columns['type']);
	//unset($columns['date_new']);
    $columns['author'] = 'Author';
    $columns['add_to_page'] = 'Add to Page';
    return $columns;
}

 
function fbtab_render_post_columns($column_name, $id) {
    switch ($column_name) {
    /*
	case 'author':
   	 // show widget set
   	 $post = get_post($id);
   	 echo $post->post_author;
   	 break;
    */
	case 'add_to_page':
	$custom_fields = get_post_custom($id);
	if(!empty($custom_fields["_appid"][0]))
		echo sprintf('<a target="_blank" href="https://www.facebook.com/dialog/pagetab?app_id=%s
&redirect_uri=%s">Add to Page</a>',$custom_fields["_appid"][0],urlencode('https://www.facebook.com') );//&display=popup
   	 else
	 	echo '<a href="'.admin_url("post.php?post=$id&action=edit#tabinstall").'">Set App ID</a>';		 
   	 break;
    case 'date_new':
	 the_modified_date();
	 break;
    }
}

//call register settings function
add_action( 'admin_init', 'register_fbtab_settings' );

function register_fbtab_settings() {
	//register our settings
	register_setting( 'fbtab-settings-group', 'fbtabset' );
	register_setting( 'fbtab-settings-group', 'fbt_remove_filter' );
	register_setting( 'fbtab-settings-group', 'fbt_remove_head' );
	register_setting( 'fbtab-settings-group', 'fbt_remove_footer' );
	//register_setting( 'fbtab-settings-group', 'fbt_theme' );
}

function save_fbt_theme () {
if(isset($_POST["fbt_theme"]))
	{
	$p = explode(":",$_POST["fbt_theme"]);
	update_option('fbt_theme',$p[0]);
	update_option('fbt_template',$p[1]);
	}
}

add_action('admin_init','save_fbt_theme');

function fbtab_settings_page() {
?>
<div class="wrap">
<h2>Facebook Tab Manager</h2>
<p>Set your defaults</p>
<form method="post" action="options.php">
<?php settings_fields( 'fbtab-settings-group' );
	
	$fbtabset = get_option('fbtabset');
	$remove_filter = get_option('fbt_remove_filter');
	$remove_head = get_option('fbt_remove_head');
	$remove_footer = get_option('fbt_remove_footer');
	$fbt_theme = get_option('fbt_theme');
	
	if($fbtabset)
	foreach ($fbtabset as $name => $value)
		$custom_fields['_'.$name][0] = $value;
	
	if($remove_filter)
		$custom_fields["_remove_filter"] = $remove_filter;
	if($remove_head)
		$custom_fields["_remove_head"] = $remove_head;
	if($remove_footer)
		$custom_fields["_remove_footer"] = $remove_footer;


	fbtab_options_ui($custom_fields, true);

?>
<p>Facebook Theme: <?php 

  // based on code from Vladimir Prelovac's Theme Test Drive
  $themes = get_themes();
     
  if (count($themes) > 1) {
	  $theme_names = array_keys($themes);
	  natcasesort($theme_names);
	  
	 
	  $ts = '<select name="fbt_theme"><option value="">Plugin Built-In</option>' . "\n";
	  foreach ($theme_names as $theme_name) {
		  // Skip unpublished themes.
		  if (isset($themes[$theme_name]['Status']) && $themes[$theme_name]['Status'] != 'publish') {
			  continue;
		  }

		  if ($themes[$theme_name]["Stylesheet"] == $fbt_theme) {
			  $ts .= '        <option value="' . $themes[$theme_name]["Stylesheet"].':'.$themes[$theme_name]["Template"] . '" selected="selected">' . htmlspecialchars($theme_name) . ' ('.$themes[$theme_name]["Stylesheet"].')</option>' . "\n";
		  } else {
			  $ts .= '        <option value="' . $themes[$theme_name]["Stylesheet"].':'.$themes[$theme_name]["Template"] . '">' . htmlspecialchars($theme_name) . '</option>' . "\n";
		  }
	  }
	  $ts .= '    </select>' . "\n\n";
  }
  echo $ts;

?>
<p><input type="checkbox" name="fbtabset[allow_authors]" value="1" <?php if(isset($fbtabset["allow_authors"]) || $fbtabset["allow_authors"]) echo ' checked="checked" '; ?> /> Allow authors to create Facebook tabs<br />
<input type="checkbox" name="fbtabset[allow_contributors]" value="1" <?php if(isset($fbtabset["allow_contributors"]) || $fbtabset["allow_contributors"]) echo ' checked="checked" '; ?> /> Allow contributors to submit Facebook tabs for approval<br />

<em>By default, only administrators and editors are allowed to create/edit these posts.</em></p>
    
    <p class="submit">
    <input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" />
    </p>

</form>
</div>
<?php
}


function fbtab_options_ui($custom_fields = NULL, $settings=false) {

if($settings)
	{
	$wrapper = "fbtabset";
	$filteropt = 'fbt_';
	}
else
	$wrapper = "fbtset";

?>
<br />
CSS Styles:<br /> 
<textarea name="<?php echo $wrapper; ?>[style]" cols="60" rows="2"><?php if(!empty($custom_fields["_style"][0])) echo $custom_fields["_style"][0]; ?></textarea><br />
<a target="_blank" href="<?php echo home_url('?fbtab_css=1&show=text'); ?>">See default styles</a><br />
Example, colored backround, margin and padding on content:
<pre>body {background-color: #626eba;}
div#content {background-color: #fff; margin: 20px; padding: 20px;}</pre>
<br />
More code to add to head (External Styles):<br /> 
<textarea name="<?php echo $wrapper; ?>[inchead]" cols="60" rows="2"><?php if(!empty($custom_fields["_inchead"][0])) echo $custom_fields["_inchead"][0]; ?></textarea><br />
<br />
More code to add to footer (Scripts, Links):<br /> 
<textarea name="<?php echo $wrapper; ?>[incfooter]" cols="60" rows="2"><?php if(!empty($custom_fields["_incfooter"][0])) echo $custom_fields["_incfooter"][0]; ?></textarea><br />

<input type="checkbox" name="<?php echo $wrapper; ?>[new_window]" value="1" <?php if(!empty($custom_fields["_new_window"][0])) echo ' checked="checked" '; ?>  />
Open Links / Post Forms to New Window<br />
<input type="checkbox" name="<?php echo $wrapper; ?>[wp_head]" value="1" <?php if(!empty($custom_fields["_wp_head"][0])) echo ' checked="checked" '; ?>  />
Template should execute theme/plugin code in HTML head - wp_head() command<br />
<input type="checkbox" name="<?php echo $wrapper; ?>[wp_footer]" value="1" <?php if(!empty($custom_fields["_wp_footer"][0])) echo ' checked="checked" '; ?>  />
Template should execute theme/plugin code in page footer - wp_footer()<br />
<input type="checkbox" name="<?php echo $wrapper; ?>[resize]" value="1" <?php if(!empty($custom_fields["_resize"][0])) echo ' checked="checked" '; ?>  />
Set resize/autoresize (for tabs taller than 800 pixels)<br />
<input type="checkbox" name="<?php echo $wrapper; ?>[hide_title]" value="1" <?php if(!empty($custom_fields["_hide_title"][0])) echo ' checked="checked" '; ?>  />
Hide post title, only show post content words / images<br />
<input type="checkbox" name="<?php echo $wrapper; ?>[minfilters]" value="1" <?php if(!empty($custom_fields["_minfilters"][0])) echo ' checked="checked" '; ?>  />
Remove all but essential filters on post content<br />
<br />
</p>
<?php
if(!empty($custom_fields["_template"][0]))
	echo '<p style="color: red;">Facebook Tab Manager has a new <a href="http://facebooktabmanager.com/2011/09/themes-and-templates-for-facebook-tab-manager-for-wordpress/">theme/template model</a>. Custom templates from before version 2.8.7 are no longer supported.</p>';
?>

<p>For documentation on these options, see <a href="http://facebooktabmanager.com">plugin homepage</a>.</p>
<p><button id="showadvanced">Show Advanced</button> - turn off selected WordPress filters and actions</p>
<script>
    jQuery("#showadvanced").click(function () {
    jQuery("#fbtab_filters").show("slow");
	return false;
    });
</script>

<div id="fbtab_filters" style="display: none;">
<p><em>In general, you only need to fuss with these options if you are seeing inappropriate content or styling appearing within your Facebook Tab Manager posts and need to selectively turn off features of your theme or plugin that are causing conflicts. Example: If a plugin is filtering post content to add links or icons that you do not want to appear on your Facebook tab. Or, you want the wp_head actions to run so you get the benefit of JavaScript libraries being loaded, but you need to disable an action that inserts a background image via CSS styling.</em></p>

<p>Check the items you wish to DISABLE. If you have not checked above to execute wp_head or wp_footer actions, you can ignore those sections.</p>
<?php

global $wp_filter;

$corefilters = array('convert_chars','wpautop','wptexturize');

echo "<p><strong>DISABLE the_content filters</strong></p><p>You can selectively deactivate filters that work on the body of a post, such as those that add social media icons you may not want included. Filters shown in bold represent core formatting functions. You should also avoid turning off filters related to shortcodes or autoembeds if these will be used in your Facebook tab posts.</p>";
if(!empty($wp_filter["the_content"]))
foreach($wp_filter["the_content"] as $filterarray)
	{
	foreach($filterarray as $name => $details)
		{
		if(in_array($name,$corefilters) )
			$namelabel = "<strong>$name</strong>";
		else
			$namelabel = $name;
		if(is_array($custom_fields["_remove_filter"]) && in_array($name,$custom_fields["_remove_filter"]) )
			$c = ' checked="checked" ';
		else
			$c = '';
		echo '<input type="checkbox" name="'.$filteropt.'remove_filter[]" value="'.$name.'" '.$c.'>'.$namelabel."<br />";
		}
	}

echo "<p><strong>DISABLE wp_head actions</strong></p>";
if(!empty($wp_filter["wp_head"]))
foreach($wp_filter["wp_head"] as $filterarray)
	{
	foreach($filterarray as $name => $details)
		{
		if(is_array($custom_fields["_remove_head"]) && in_array($name,$custom_fields["_remove_head"]) )
			$c = ' checked="checked" ';
		else
			$c = '';
		echo '<input type="checkbox" name="'.$filteropt.'remove_head[]" value="'.$name.'" '.$c.'>'.$name."<br />";
		}
	}

echo "<p><strong>DISABLE wp_footer actions</strong></p>";
if(!empty($wp_filter["wp_footer"]))
foreach($wp_filter["wp_footer"] as $filterarray)
	{
	foreach($filterarray as $name => $details)
		{
		if(is_array($custom_fields["_remove_footer"]) && in_array($name,$custom_fields["_remove_footer"]) )
			$c = ' checked="checked" ';
		else
			$c = '';
		echo '<input type="checkbox" name="'.$filteropt.'remove_footer[]" value="'.$name.'" '.$c.'>'.$name."<br />";
		}
	}
	
	echo '</div>';
}

add_action( 'init', 'create_fbtab_post_type' );

function create_fbtab_post_type() {
  register_post_type( 'fbtab',
    array(
      'labels' => array(
        'name' => __( 'Facebook Tabs' ),
        'add_new_item' => __( 'Add New Facebook Tab' ),
        'edit_item' => __( 'Edit Facebook Tab' ),
        'new_item' => __( 'Facebook Tabs' ),
        'singular_name' => __( 'Facebook Tab' )
      ),
	'public' => true,
	'exclude_from_search' => true,
    'publicly_queryable' => true,
    'show_ui' => true, 
    'query_var' => true,
    'rewrite' => true,
    'capability_type' => 'fbtab',
    'map_meta_cap' => true,
    'hierarchical' => true,
    'has_archive' => true,
    'menu_position' => 5,
	'menu_icon' => plugins_url('/facebook.png',__FILE__),
    'supports' => array('title','editor')
    )
  );

// if there is a logged in user, set editing roles
global $current_user;
if( isset($current_user) )
	fbtab_roles();
	
}


function fbtab_roles() {

$fbroles = array('administrator','editor');
$fbtabset = get_option('fbtabset');
if(isset($fbtabset["allow_authors"]) && $fbtabset["allow_authors"])
	$fbroles[] = 'author';
if(isset($fbtabset["allow_contributors"]) && $fbtabset["allow_contributors"])
	$fbroles[] = 'contributor';

global $wp_roles;

if(!isset($wp_roles) )
	$wp_roles = new WP_Roles();
if(isset($wp_roles->roles))
foreach ($wp_roles->roles as $role => $rolearray)
	{
	if(in_array($role, array('administrator','editor','author','contributor') ) )
	foreach($rolearray["capabilities"] as $cap => $flag)
		{
			if(strpos($cap,'post') )
				{
					$fbcap = str_replace('post','fbtab',$cap);
					if(in_array($role,$fbroles) )
						$wp_roles->add_cap( $role, $fbcap );
					else
						$wp_roles->remove_cap( $role, $fbcap );
				}
		}
	}
}

add_action("template_redirect", 'fbtab_template_redirect');

//before we parse the request, let's make sure those rewrite rules are up to date
if(!is_admin() )
	add_action("wp_loaded",'flush_rewrite_rules');

// Template selection
function fbtab_template_redirect()
{
	
	if ( is_fbtab() )
		fbtab_template_setup(); // adjustments for which filters and actions should run
		
	$fbt_theme = get_option('fbt_theme');
	if($fbt_theme)
		return;

	global $wp_query;
	global $wp;
	
	if ( is_fbtab() )
	{
		if (have_posts())
		{
			include(WP_PLUGIN_DIR . '/facebook-tab-manager/fbtab-theme/index.php');
			die();
		}
		else
		{
			$wp_query->is_404 = true;
		}

	}
}

function my_fbtab_menu() {
add_meta_box( 'fbtabbox', 'Options', 'draw_taboptions', 'fbtab', 'normal', 'high' );
}

add_action('admin_menu', 'my_fbtab_menu');

function save_fbtab_data($postID) {

if(!isset($_POST["fbtabdata"]))
	return;
// we only care about saving fbtabdata

	global $wpdb;
	global $current_user;
	
	if($parent_id = wp_is_post_revision($postID))
		{
		$postID = $parent_id;
		}

		$checkboxes = array('wp_head','wp_footer','new_window','resize','hide_title','minfilters');
		foreach($checkboxes as $index)
			if(!isset($_POST["fbtset"][$index]))
				$_POST["fbtset"][$index] = 0;
		
		foreach($_POST["fbtset"] as $name => $value)
			{
			$field = '_'.$name;
			$single = true;
			$current = get_post_meta($postID, $field, $single);
			 
			if($value && ($current == "") )
				add_post_meta($postID, $field, $value, true);
			
			elseif($value != $current)
				update_post_meta($postID, $field, $value);
			
			elseif($value == "")
				delete_post_meta($postID, $field, $current);
			}

	if(isset($_POST["remove_filter"]))
		$remove_filter = $_POST["remove_filter"];
	else
		$remove_filter = array(); // empty array

	$field = '_remove_filter';
	$single = false;
	$current = get_post_meta($postID, $field, $single);
	
	if(is_array($current) )
		{
		$delete = array_diff($current, $remove_filter);
		if(is_array($delete))
			foreach($delete as $d)
				delete_post_meta($postID, $field, $d);
		}
	
	foreach($remove_filter as $value)
		{

			if(is_array($current) && (!in_array($value,$current)) )
				{
				add_post_meta($postID, $field, $value, $single);
				}
		}

	if(isset($_POST["remove_head"]))
		$remove_head = $_POST["remove_head"];
	else
		$remove_head = array(); // empty array
	$field = '_remove_head';
	$single = false;
	$current = get_post_meta($postID, $field, $single);
	
	if(is_array($current) )
		{
		$delete = array_diff($current, $remove_head);
		if(is_array($delete))
			foreach($delete as $d)
				delete_post_meta($postID, $field, $d);
		}
	
	foreach($remove_head as $value)
		{

			if(is_array($current) && (!in_array($value,$current)) )
				{
				add_post_meta($postID, $field, $value, $single);
				}
		}

	if(isset($_POST["remove_footer"]))
		$remove_footer = $_POST["remove_footer"];
	else
		$remove_footer = array(); // empty array
	$field = '_remove_footer';
	$single = false;
	$current = get_post_meta($postID, $field, $single);
	
	if(is_array($current) )
		{
		$delete = array_diff($current, $remove_footer);
		if(is_array($delete))
			foreach($delete as $d)
				delete_post_meta($postID, $field, $d);
		}
	
	foreach($remove_footer as $value)
		{

			if(is_array($current) && (!in_array($value,$current)) )
				{
				add_post_meta($postID, $field, $value, $single);
				}
		}
}

add_action('save_post','save_fbtab_data');

add_filter('mce_css', 'fbtab_mce_css');
function fbtab_mce_css($style) {
global $post;
if(($post->post_type == 'fbtab') || (isset($_GET["post_type"]) && ($_GET["post_type"] == 'fbtab') ) )
  return home_url('?fbtab_css=1&postid='.$post->ID);
 else
 	return $style;
}


function fblike($atts, $content = NULL ) {
if(!isset($atts["like"]) )
	$atts["like"] = 1;
$atts["nodecode"] = 1;
return fbtab_shortcode($atts, $content);
}

add_shortcode('fblike','fblike');

function fbtab_shortcode($atts, $content = NULL ) {

global $post;

$atts = shortcode_atts( array(
  'nodecode' => 0,
  'getpostid' => 0,
  'minfilters' => 0,
  'query' => NULL,
  'format' => NULL,
  'like' => NULL,
  'message' => NULL,
  ), $atts );

$nodecode = (int) $atts["nodecode"];

/* Like gate no longer works
if(isset($atts["like"]))
	{}
*/

if($atts["query"])
	{

		$donotrepeat = $post->ID;
	ob_start();
	$querystring = html_entity_decode($atts["query"]);
	
	$fbq = new WP_Query($querystring);

if ( $fbq->have_posts() ) {
while ( $fbq->have_posts() ) : $fbq->the_post(); 

if($post->ID == $donotrepeat)
	continue; // no infinite loops
?>
<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
<h1 class="entry-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h1>
<?php
if(isset($atts["format"]) && ($atts["format"] == 'headline'))
	;
elseif(isset($atts["format"]) && ($atts["format"] == 'excerpt'))
	{
?>
<div class="excerpt-content">

<?php the_excerpt(); ?>

</div><!-- .excerpt-content -->
<?php	
	}
else
	{
?>
<div class="entry-content">

<?php the_content(); ?>

</div><!-- .entry-content -->
<?php
}
?>
</div>
<?php 
endwhile;
?>
<p><?php

} 
	
	$content = ob_get_clean();
	}
elseif($atts["getpostid"])
	{
	ob_start();
	$id = (int) $atts["getpostid"];
	$include = get_post($id);
?>
<div id="post-<?php echo $id; ?>" >
<h1 class="entry-title"><?php echo apply_filters('the_title',$include->post_title); ?></h1>
<div class="entry-content">
<?php echo apply_filters('the_content',$include->post_content); 
?>
</div><!-- .entry-content -->
</div>
<?php 
	$content = ob_get_clean();
	}
elseif($nodecode)
	;
elseif($content)
	{

	if(strpos($content,'&lt;script') )
		$content = str_replace("\n"," ",$content);

	//check for numeric references, variants on quotation marks entities
	$content = preg_replace('/&(#8220|#8221|#8243|quot|ldquo|rdquo);/','"',$content);
	$content = preg_replace('/&(#8216|#8217|apos|lsquo|rsquo);/',"'",$content);

	//adwords tweak
	$content = str_replace('&#215;',"x",$content);
	$content = str_replace('&lt;!&#8211;',"<!-- \n",$content);
	$content = str_replace('&#8211;&gt;',"-->\n",$content);
	$content = str_replace('/*',"\n/* ",$content);
	$content = str_replace('*/'," */ \n",$content);

	//convert standard entities including <>
	$content = html_entity_decode($content);
	$content = '<div class="fbtab-widget">'.$content.'</div>';
	
	}

wp_reset_postdata(); // put globals back where you found them
	
	return do_shortcode($content);
}

add_shortcode('fbtab', 'fbtab_shortcode');

function create_canvas() {
if(!isset($_GET["create_canvas"]) )
	return;
// if this is a create canvas call, continue
$up = explode('&create_canvas',$_SERVER['REQUEST_URI']);
$redirect = "Location: ".$up[0];
if(isset($_GET["post"]) )
	$postid = (int) $_GET["post"];
$canvas = get_post($postid);

if($canvas->post_type != 'fbtab')
	return;

$tabarray = array(
'post_status' => 'publish', 
'post_type' => 'fbtab',
'post_author' => $canvas->post_author,
'post_parent' => $postid,
'post_title' => $canvas->post_title,
'post_content' => $canvas->post_content,
'post_name' => 'tab'.$postid
);
$tabid = wp_insert_post( $tabarray );

$custom = get_post_custom($postid);
if($custom)
foreach ($custom as $field => $value)
	{
	if($field == '_remove_filter')
		{
		foreach($value as $v)
			{
			add_post_meta($tabid, $field, $v, false);
			}
		}
	elseif($field == '_canvas')
		; // ignore
	else
		add_post_meta($tabid, $field, $value[0], true);
	}

add_post_meta($postid, '_canvas', 1, true);

header($redirect);
exit();

}

add_action('admin_init','create_canvas');

function fbtab_doc() {
?>
<div class="wrap"> 
	<div id="icon-edit" class="icon32"><br /></div>
<h2>Documentation and Support</h2>

<script type="text/javascript"><!--
google_ad_client = "ca-pub-2472900457797457";
/* 468x60, created 8/15/10 */
google_ad_slot = "0072757880";
google_ad_width = 468;
google_ad_height = 60;
//-->
</script>
<script type="text/javascript"
src="http://pagead2.googlesyndication.com/pagead/show_ads.js">
</script>

<p>If you feel like saying thank you to the plugin author ... </p>
<form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_blank">
<input type="hidden" name="cmd" value="_s-xclick">
<input type="hidden" name="hosted_button_id" value="N6ZRF6V6H39Q8">
<input type="image" src="https://www.paypal.com/en_US/i/btn/btn_donateCC_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
<img alt="" border="0" src="https://www.paypal.com/en_US/i/scr/pixel.gif" width="1" height="1">
</form>

<p>See the plugin home page at <a href="http://facebooktabmanager.com">facebooktabmanager.com</a> for documentation, tutorials, updates on new developments, and a showcase of tabs others have created.</p>
<p>Follow the discussion at the <a href="http://www.facebook.com/carrcomm">Carr Communications Facebook page</a> and Like our page.</p>
<p>Ask support questions on the <a href="http://wordpress.org/tags/facebook-tab-manager">WordPress.org support forum for Facebook Tab Manager</a>.</p>

<p>For consulting inquiries, contact David F. Carr at <a href="http://www.carrcommunications.com">Carr Communications.com</a>
<br />LinkedIn: <a href="http://www.linkedin.com/in/davidfcarr">http://www.linkedin.com/in/davidfcarr</a></p>
<p>&nbsp;</p>
<script type="text/javascript"><!--
google_ad_client = "ca-pub-2472900457797457";
/* 468x60, created 8/15/10 */
google_ad_slot = "0072757880";
google_ad_width = 468;
google_ad_height = 60;
//-->
</script>
<script type="text/javascript" src="http://pagead2.googlesyndication.com/pagead/show_ads.js">
</script>
<?php
}

function fbtab_admin_menu() {
global $rsvp_options;
add_submenu_page('edit.php?post_type=fbtab', "Documentation", "Documentation", 'edit_posts', "fbtab_doc", "fbtab_doc");
add_submenu_page('edit.php?post_type=fbtab', "Add to Page Links", "Add to Page Links", 'edit_posts', "add_to_page_admin", "add_to_page_admin" );
}

add_action('admin_menu', 'fbtab_admin_menu');

function reveal_where ($where) {
$where = str_replace("post_type = 'post'","post_type LIKE '%'",$where);
return $where;
}

function fbreveal () {
// legacy, leaving for now
if(isset($_GET['preloader']) )
{
$uri = preg_replace('/preloader.+/','',$_SERVER['REQUEST_URI']);
fbtab_output_preloader ($uri);
exit();	
}

if(!isset($_GET["fbreveal"]))
	return;
add_filter('posts_where','reveal_where');
$parts = preg_split('/[^0-9]/',$_GET["fbreveal"]);
$liked = false; // legacy
$id = ($liked) ? $parts[1] : $parts[0];
$permalink = get_permalink($id);
$permalink .= (strpos($permalink,'?') ) ? "&fb=tab" : "?fb=tab";
foreach($_GET as $name => $value)
	{
	if($name != 'fbreveal')
		$permalink .= '&'.$name . '=' . $value;
	}
if(isset($_GET["redirect"]))
	header("Location: $permalink");
else
	fbtab_output_preloader ($permalink);
exit();	
}

add_action('init','fbreveal');

function https_content ($content) {
return $content = str_replace("src=\"http://".$_SERVER["SERVER_NAME"],"src=\"https://".$_SERVER["SERVER_NAME"],$content);
}

function fbtab_template_setup() {
global $wp_filter;
global $post;
global $fbtab_custom_fields;

$fbtab_custom_fields = get_post_custom($post->ID);
if(isset($_SERVER['HTTPS']) && ($_SERVER['HTTPS'] == 'on'))
	{
	$style = str_replace('http:','https:',$style);
	$fbtab_custom_fields["prefix"] = "https";
	add_filter('the_content','https_content');
	}
else
	$fbtab_custom_fields["prefix"] = "http";

if(isset($fbtab_custom_fields["_remove_filter"]))
{
foreach($wp_filter["the_content"] as $priority => $filters)
	foreach($filters as $name => $details)
		$p[$name] = $priority;
foreach($fbtab_custom_fields["_remove_filter"] as $filter)
	{
	remove_filter( 'the_content', $filter, $p[$filter] );
	remove_filter( 'the_excerpt', $filter, $p[$filter] );
	}
}

if(isset($_GET["minfilters"]) || isset($fbtab_custom_fields["_minfilters"][0]))
{
$corefilters = array('convert_chars','wpautop','wptexturize');
foreach($wp_filter["the_content"] as $priority => $filters)
	foreach($filters as $name => $details)
		{
		//keep only core text processing or shortcode
		if(!in_array($name,$corefilters) && !strpos($name,'hortcode'))
			{
			$r = remove_filter( 'the_excerpt', $name, $priority );
			$r = remove_filter( 'the_content', $name, $priority );
			}
		}
}

if(isset($fbtab_custom_fields["_remove_head"]))
	{
	foreach($wp_filter["wp_head"] as $priority => $filters)
		foreach($filters as $name => $details)
			$p[$name] = $priority;
	
	foreach($fbtab_custom_fields["_remove_head"] as $filter)
		remove_filter( 'wp_head', $filter, $p[$filter] );
	}

if(isset($fbtab_custom_fields["_remove_footer"]))
	{
	foreach($wp_filter["wp_footer"] as $priority => $filters)
		foreach($filters as $name => $details)
			$p[$name] = $priority;
	
	foreach($fbtab_custom_fields["_remove_footer"] as $filter)
		remove_filter( 'wp_footer', $filter, $p[$filter] );
	}
}

function fbtab_head() {
global $fbtab_custom_fields;

//don't run more than once
static $fbtab_head_done;
if($fbtab_head_done != NULL)
	return;
$fbtab_head_done = true;

if(isset($fbtab_custom_fields["_new_window"][0]) || isset($_GET["new"]))
{
?>
<base target="_blank" />
<?php
}
if(isset($fbtab_custom_fields["_wp_head"][0]) || isset($_GET["head"]))
	wp_head();

if(isset($fbtab_custom_fields["_canvas"][0]) || isset($_GET["canvas"]))
	$width = '720px;';
elseif( $fbtab_custom_fields["_layout"][0] == 'timeline' )
	$width = '810px;';
else
	$width = '520px;';
?>
<style type="text/css" media="all">
/* <![CDATA[ */
body {width: <?php echo $width; ?>; overflow:hidden;}
<?php echo $fbtab_custom_fields["_style"][0]; ?>

/* ]]> */
</style>
<?php 
if(isset($fbtab_custom_fields["_inchead"][0]))
	echo $fbtab_custom_fields["_inchead"][0]; 
}

function fbtab_title() {
global $fbtab_custom_fields;

if(!isset($fbtab_custom_fields["_hide_title"][0]))
	{
?>
<h1 class="entry-title"><?php the_title(); ?></h1>
<?php
	}

}

function wp_fbtab_footer() {
if(!is_fbtab() )
	return;
fbtab_footer();
}

function wp_fbtab_head() {
if(!is_fbtab() )
	return;
fbtab_head();
}

add_action('wp_footer','wp_fbtab_footer',99);
add_action('wp_head','wp_fbtab_head',99);

function fbtab_footer() {

//don't run more than once
static $fbtab_footer_done;
if($fbtab_footer_done != NULL)
	return;
$fbtab_footer_done = true;

global $from_wp_footer;
global $fbtab_custom_fields;

if(isset($fbtab_custom_fields["_resize"][0]) || isset($_GET["resize"]))
	{
?>
<script type="text/javascript" src="https://connect.facebook.net/en_US/all.js"></script>
<script type="text/javascript" charset="utf-8">
<?php
if($fbtab_custom_fields["_appid"][0] || $_GET["appid"])
{
?>
  FB.init({
    appId      : '<?php echo ($fbtab_custom_fields["_appid"][0]) ? $fbtab_custom_fields["_appid"][0] : $_GET["appid"];?>', 
    channelUrl : '<?php echo plugins_url('facebook-tab-manager/channel.php'); ?>',
      status     : true,
      cookie     : true,
      xfbml      : true
  });

<?php
}
?>

window.onload = function() {
FB.Canvas.setAutoGrow();
}
</script>
<?php
	}
if(isset($fbtab_custom_fields["_incfooter"][0]))
	echo $fbtab_custom_fields["_incfooter"][0];

if(empty($from_wp_footer))
	{
	// if this is invoked from wp_footer, don't repeat
	if(isset($fbtab_custom_fields["_wp_footer"][0])  || isset($_GET["footer"]))
		wp_footer();

	}

}



function get_fbtab_templates() {
	$theme = get_option('fbt_theme');
	if(!$theme)
		return false;
	$directory = get_theme_root() . '/'.$theme;
	$d = dir($directory);
	$page_templates = array();
	while (false !== ($entry = $d->read())) {
	   if( strpos($entry,'php') && !strpos($entry,'ndex.php') && !strpos($entry,'unctions.php') )
	   	{
			$template_data = file_get_contents($directory . '/'. $entry);
			$name = '';
			if ( preg_match( '|Template Name:(.*)$|mi', $template_data, $name ) )
				$name = _cleanup_header_comment($name[1]);
			if ( !empty( $name ) )
				$page_templates[trim( $name )] = $entry;
		}
	}
	$d->close();
	return $page_templates;
}

//work in progress
function fbtab_output_preloader ($uri) {
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
   "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd"><html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en" > 
<head><meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>Loader</title> 
        <script type="text/javascript" src="<?php echo includes_url('/js/jquery/jquery.js'); ?>"></script> 
        <script type="text/javascript">
            jQuery(document).ready(function($){
                $('#loader').load('<?php echo $uri; ?>', '', function(response, status, xhr) {
                    if (status == 'error') {
                        var msg = "Sorry but there was an error: ";
                        $(".content").html(msg + xhr.status + " " + xhr.statusText);
                    }
                });
            });
        </script> 
    </head>
    <body> 
        <div id="loader">Loading ...
        <br /><img src="<?php echo plugins_url('loading.gif',__FILE__); ?>" >
</div>
<?php
fbtab_footer();
?>
    </body>
</html>
<?php
}

function get_fbtab_template($template) {
	if ( is_fbtab() )
	{
		$id = get_queried_object_id();
		$template = get_post_meta($id, '_wp_page_template', true);
		$pagename = get_query_var('pagename');
	
		if ( !$pagename && $id > 0 ) {
			// If a static page is set as the front page, $pagename will not be set. Retrieve it from the queried object
			$post = get_queried_object();
			$pagename = $post->post_name;
		}
	
		if ( 'default' == $template )
			$template = '';
	
		$templates = array();
		if ( !empty($template) && !validate_file($template) )
			$templates[] = $template;
		if ( $pagename )
			$templates[] = "fbtab-$pagename.php";
		if ( $id )
			$templates[] = "fbtab-$id.php";
		$templates[] = 'fbtab.php';
		$templates[] = 'index.php';
	
		$template = get_query_template( 'fbtab', $templates );
	}
	return $template;
}



function fbt_get_template($t) {
if ( is_fbtab() )
	{
	$fbt_template = get_option('fbt_template');
	if($fbt_template)
		return $fbt_template;
	
	$fbt_theme = get_option('fbt_theme');
	if($fbt_theme)
		return $fbt_theme;
	}
return $t;
}

function fbt_get_stylesheet($t) {

if ( is_fbtab() )
	{
	
	$fbt_theme = get_option('fbt_theme');
	if($fbt_theme)
		return $fbt_theme;
	}

return $t;
}

add_filter('template', 'fbt_get_template',99);
add_filter('stylesheet', 'fbt_get_stylesheet',99);
add_filter('template_include', 'get_fbtab_template');

function fbplugin($atts) {

$js = '<div id="fb-root"></div>
<script>(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) {return;}
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/en_US/all.js#xfbml=1";
  fjs.parentNode.insertBefore(js, fjs);
}(document,'." 'script', 'facebook-jssdk'));</script>\n";

if(empty($atts["type"]))
	return;
if($atts["type"] == 'comments')
	return $js.'<div class="fb-comments" data-href="'.$atts["url"].'" data-num-posts="10" data-width="500"></div>';
elseif($atts["type"] == 'like-send')
	return $js.'<div class="fb-like" data-href="'.$atts["url"].'" data-send="true" data-width="500" data-show-faces="true"></div>';
}

add_shortcode('fbplugin','fbplugin');

function add_to_page_admin () {

if(isset($_POST["appid"]) )
	{
	$addlinks = sprintf('<p><a target="_blank" href="https://www.facebook.com/dialog/pagetab?app_id=%s
&redirect_uri=%s">Add to Page - %s</a></p>'."\n",$_POST["appid"],urlencode($_POST["redirect"]), $_POST["title"] );//&display=popup
	$addlinks .= get_option('addtopagelinks');
	update_option('addtopagelinks',$addlinks);
	}
else
	$addlinks = get_option('addtopagelinks');	
	
//"_appid"][0]
?>
<div class="wrap"> 
	<div id="icon-edit" class="icon32"><br /></div>
<h2>Add to Page Links</h2>

<p>Once you have registered your page tabs with the Facebook developer tool, you must record the App ID in Facebook Tab Manager in order to get the link you click to add your custom tabs to one or more Facebook pages. The editor and the Reveal Tab Utility now allow you record the App ID, and a summary of those links will appear at the bottom of this page. You can also use the utility below to record the App ID for other custom tabs, along with a title to remind you what it is associated with.</p>

<p>For example, using the technique of adding the query string to the end of an existing post, you could register <?php $url = site_url().'?fb=tab&amp;minfilters=1&amp;resize=1'; printf('<a target="_blank" href="%s">%s</a>',$url,$url); ?> and record the App ID with the note &quot;home page in FBTab template.&quot;</p>

<p>At the end of 2011, Facebook eliminated the application profile pages where the Add to Page button used to be displayed.</p>

<form action="<?php echo $_SERVER['REQUEST_URI']; ?>" method="post">
<table>
<tr><td>App ID:</td><td><input name="appid"  /></td></tr> 
<tr><td>Redirect To:</td><td><input name="redirect" value="https://www.facebook.com"  size="50" /></td></tr>
<tr><td>Title:</td><td><input name="title" value="" size="50" /></td></tr>
</table>
    <p class="submit">
    <input type="submit" class="button-primary" value="<?php _e('Create Add to Page Link') ?>" />
    </p>
</form>
<?php
echo $addlinks;

global $wpdb;
$results = $wpdb->get_results("SELECT meta_value, post_title  FROM `wp_postmeta` join wp_posts ON post_id = wp_posts.id WHERE `meta_key` LIKE '_appid'");
if($results)
	{
	echo "<h3>These tabs have their App ID set through the editor.</h3>";
	foreach($results as $row) {
		printf('<p><a target="_blank" href="https://www.facebook.com/dialog/pagetab?app_id=%s
		&redirect_uri=%s">Add to Page - %s</a></p>'."\n",$row->meta_value, urlencode('https://www.facebook.com'), $row->post_title );
		}
	}
?>
</div>

<?php
}

function fbtab_width_test () {
for($width = 500; $width < 820; $width+=5)
	printf('<div style="font-size: 40px; width: %dpx; border: thin solid #000; margin-bottom: 5px;">%d pixels</div>',$width,$width);
}

add_shortcode('fbtab_width_test','fbtab_width_test');

function fbtab_nav_menu ($args) {

if(!empty($args["css_id"]))
	$css_id = ' id ="'.$args["css_id"].'" ';	
$args["echo"] = false;

return sprintf('<div class="fbtab_menu" %s>'."\n%s\n</div>",$css_id,wp_nav_menu($args));
}

add_shortcode('fbtab_nav_menu','fbtab_nav_menu');

function fbtab_no_related_posts( $options ) {
	global $post;
	//prevent Jetpack Related posts output
    if ( $post->post_type == 'fbtab' ) {
        $options['enabled'] = $options['headline'] = false;
    }
    return $options;
}
add_filter( 'jetpack_relatedposts_filter_options', 'fbtab_no_related_posts' );
?>