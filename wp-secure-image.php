<?php
/*
Plugin Name: Secure Image
Plugin URI: https://artistscope.com/secure_image_protection.asp
Description: Download protect images by using encrypted images and controlle browser access. With Secure Image you can use encrypted images and extend copy protection to prevent image saving while displayed online and stored on the server, even from your webmaster.
Author: ArtistScope
Version: 2.0
Author URI: http://www.artistscope.com/

	Copyright 2024 ArtistScope Pty Limited


	This program is free software: you can redistribute it and/or modify
	it under the terms of the GNU General Public License as published by
	the Free Software Foundation, either version 3 of the License, or
	any later version.

	This program is distributed in the hope that it will be useful,
	but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	GNU General Public License for more details.

	You should have received a copy of the GNU General Public License
	along with this program.  If not, see <http://www.gnu.org/licenses/>.
*/

// ================================================================================ //
//                                                                                  //
//  WARNING : DONT CHANGE ANYTHING BELOW IF YOU DONT KNOW WHAT YOU ARE DOING        //
//                                                                                  //
// ================================================================================ //

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

# set script max execution time to 5mins
//set_time_limit(300);

// ============================================================================================================================
# register WordPress menus
function wpsiw_admin_menus() {
    add_menu_page( 'Secure Image', 'Secure Image', 'publish_posts', 'wpsiw_list' );
    add_submenu_page( 'wpsiw_list', 'Secure Image All Files', 'All Files', 'publish_posts', 'wpsiw_list', 'wpsiw_admin_page_list' );
    add_submenu_page( 'wpsiw_list', 'Secure Image Settings', 'Settings', 'publish_posts', 'wpsiw_settings', 'wpsiw_admin_page_settings' );
}

// ============================================================================================================================
# "List" Page
function wpsiw_admin_page_list() {
		
    $files = _get_wpsiw_uploadfile_list();
    $table = "";
	foreach ($files as $file) {
		
		$bare_url = 'admin.php?page=wpsiw_list&siwfilename='.$file["filename"].'&action=siwdel';
		$complete_url = wp_nonce_url($bare_url, 'siwdel','siwdel_nonce');
		
		$link = "<div class='row-actions'>
					<span><a href='".$complete_url."' title=''>Delete</a></span>											
				</div>" ;
            // prepare table row
        $table.= "<tr><td></td><td>{$file["filename"]} {$link}</td><td>{$file["filesize"]}</td><td>{$file["filedate"]}</td></tr>";
    }

	if( !$table ){
		 $table.= '<tr><td colspan="3">'.__('No file uploaded yet.').'</td></tr>';
	}
?>
<div class="wrap">
    <div class="icon32" id="icon-file"><br /></div>
    <h2>List Class Files</h2>
	    <div id="col-container" style="width:700px;">        
            <div class="col-wrap">
                <h3>Uploaded Class Files</h3>
                <table class="wp-list-table widefat">
                    <thead>
                        <tr><th width="5px">&nbsp;</th><th>File</th><th>Size</th><th>Date</th></tr>
                    </thead>
                    <tbody>
                    <?php echo $table; ?>
                    </tbody>
                    <tfoot>
                        <tr><th>&nbsp;</th><th>File</th><th>Size</th><th>Date</th></tr>
                    </tfoot>
                </table>
            </div>
	    </div>
            <div class="clear"></div>
        </div>
<?php
}

// ============================================================================================================================
# "Settings" page
function wpsiw_admin_page_settings() {
	$msg='';
	if ( !empty( $_POST ) )
	{
		$wpsiw_options = get_option( 'wpsiw_settings' ); 
		extract( $_POST, EXTR_OVERWRITE );

		if (!$upload_path)
		{
			$upload_dir = wp_upload_dir();
			$user_dirname = $upload_dir['basedir'];
			$upload_path = $user_dirname.'/secure-image/';
			$upload_path = str_replace( "\\", "/", stripcslashes($upload_path)) ;
		}

		if (substr($upload_path, -1) != "/")
			$upload_path .= "/";

		$wpsiw_options['settings'] = array(
			'admin_only' => sanitize_text_field($admin_only),
			'upload_path' 	=> $upload_path ,
			'max_size'	=> (int)$max_size,
			'mode'		=> sanitize_text_field($mode),
			'asps'		=> sanitize_text_field($asps),
			'ie'		=> sanitize_text_field($ie),
			'ff'		=> sanitize_text_field($ff),
			'ch'		=> sanitize_text_field($ch),
			'op'		=> sanitize_text_field($op),
			'sa'		=> sanitize_text_field($sa)
		);

		$upload_path = ABSPATH . $upload_path ;
		if (!is_dir($upload_path))
			mkdir($upload_path, 0, true);

		update_option( 'wpsiw_settings', $wpsiw_options );
		$msg = '<div class="updated"><p><strong>'.__( 'Settings Saved' ).'</strong></p></div>';
	}

	$wpsiw_options = get_option( 'wpsiw_settings' );
	if( $wpsiw_options["settings"] )
		extract( $wpsiw_options["settings"], EXTR_OVERWRITE );

	$select = '<option value="demo">Demo Mode</option><option value="licensed">Licensed</option><option value="debug">Debugging Mode</option>';
	$select = str_replace( 'value="'.$mode.'"','value="'.$mode.'" selected',$select);
?>
<div class="wrap">
    <div class="icon32" id="icon-settings"><br /></div>
    <?php echo $msg; ?>
    <h2>Settings</h2>
    <form action="" method="post">
    <table class="form-table secure-image-setting">
        <p><strong>Default settings applied to all protected pages:</strong></p>
		<?php echo wp_nonce_field('wpsiw_settings','wpsiw_wpnonce'); ?>
    	<tbody>
            <tr>
    		  <th align="left"><label>Allow Admin Only:</label></th>
	    		  <td align="left"> <input name="admin_only" type="checkbox" value="checked" <?php echo $admin_only; ?>></td>
    	    </tr>
            <tr>
	    		  <th align="left"><label>Upload Folder:</label></th>
	    		  <td align="left"> <input value="<?php echo $upload_path; ?>" name="upload_path" class="regular-text code" type="text"></td>
	    	    </tr>
	    	    <tr>
	    		  <th align="left"><label>Maximum upload size:</label></th>
	    		  <td align="left"> <input value="<?php echo $max_size; ?>" name="max_size" class="regular-text" style="width:70px;text-align:right;" type="text">&nbsp;KB</td>
    	    </tr>
        	<tr>
        		<th align="left"><label>Mode</label></th>
        		<td align="left"> <select name="mode"><?php echo $select; ?></select></td>
        	</tr>
            <tr>
	    		  <th align="left"><label>Allow ArtisBrowser:</label></th>
	    		  <td align="left"> <input name="asps" type="checkbox" value="checked" <?php echo $asps; ?>></td>
	    	    </tr>
	            <tr>
    		  <th align="left"><label>Allow IE:</label></th>
	    		  <td align="left"> <input name="ie" type="checkbox" value="checked" <?php echo $ie; ?>></td>
    	    </tr>
            <tr>
    		  <th align="left"><label>Allow Firefox:</label></th>
	    		  <td align="left"> <input name="ff" type="checkbox" value="checked" <?php echo $ff; ?>></td>
    	    </tr>
            <tr>
    		  <th align="left"><label>Allow Chrome:</label></th>
	    		  <td align="left"> <input name="ch" type="checkbox" value="checked" <?php echo $ch; ?>></td>
    	    </tr>

	            <tr>
    		  <th align="left"><label>Allow Opera:</label></th>
	    		  <td align="left"> <input name="op" type="checkbox" value="checked" <?php echo $op; ?>></td>
    	    </tr>
            <tr>
    		  <th align="left"><label>Allow Safari:</label></th>
	    		  <td align="left"> <input name="sa" type="checkbox" value="checked" <?php echo $sa; ?>></td>
    	    </tr>
    	</tbody>
    </table>
    <p class="submit">
        <input type="submit" value="Save Settings" class="button-primary" id="submit" name="submit">
    </p>
    </form>
    <div class="clear"></div>
</div>
<div class="clear"></div>
<?php
}

// ============================================================================================================================
# convert shortcode to html output
function wpsiw_shortcode( $atts )
{
	global $post ;
	$postid = $post->ID ;
	$filename = $atts["name"] ;

	if( !file_exists( WPSIW_UPLOAD_PATH . $filename ) )
		return "<div style='padding:5px 10px;background-color:#fffbcc'><strong>File($filename) don't exist</strong></div>" ;
	
	$settings = wpsiw_get_first_class_settings() ;
	
	// get plugin options
	$wpsiw_options = get_option( 'wpsiw_settings' );
	if( $wpsiw_options["settings"] )
		$settings = wp_parse_args( $wpsiw_options["settings"], $settings );
	
	if( @$wpsiw_options["classsetting"][$postid][$filename] )
		$settings = wp_parse_args( $wpsiw_options["classsetting"][$postid][$filename], $settings );
		
	$settings = wp_parse_args( $atts, $settings );

	extract( $settings ) ;

	if ($asps == "checked") {
		$asps = '1';
	}
	if ($ch == "checked") {
		$chrome = '1';
	}
	if ($ff == "checked") {
		$firefox = '1';
	}
	if ($op == "checked") {
		$opera = '1';
	}
	if ($sa == "checked") {
		$safari = '1';
	}
	if ($ie == "checked") {
		$msie = '1';
	}
	// echo $nav = ( $navigator == "checked" ) ? true : false ; 
	
	$plugin_url = WPSIW_PLUGIN_URL ;
	$upload_url = WPSIW_UPLOAD_URL ;

	// display output
	$output = <<<html
	<NOSCRIPT><meta http-equiv="refresh" content="0;url={$plugin_url}download_javascript.html"></NOSCRIPT>
	<script type="text/javascript">
		var wpsiw_plugin_url = "$plugin_url" ;
		var wpsiw_upload_url = "$upload_url" ;
	</script>
	 <script type="text/javascript">
		// hide JavaScript from non-JavaScript browsers
		var m_bpDebugging = false;
		var m_szMode = "$mode";
		var m_szClassName = "$name";
		var m_szImageFolder = "/wp-content/uploads/secure-image/";		//  path from root with / on both ends

		var m_bpKeySafe = "$key_safe";
	//	var m_bpWindowsOnly = true;	

		var m_bpASPS = "$asps";				// ASPS web browsers version 2 and later
		var m_bpChrome = "$chrome";			// all chrome browsers before version 32	
		var m_bpFx = "$firefox";			// all firefox browsers from version 5 and later
		var m_bpOpera = "$opera";
		var m_bpSafari = "$safari";
		var m_bpMSIE = "$msie";

		var m_szDefaultStyle = "ImageLink";
		var m_szDefaultTextColor = "$text_color";
		var m_szDefaultBorderColor = "$border_color";
		var m_szDefaultBorder = "$border";
		var m_szDefaultLoading = "$loading_message";
		var m_szDefaultLabel = "";
		var m_szDefaultLink = "$hyperlink";
		var m_szDefaultTargetFrame = "$target";
		var m_szDefaultMessage = "";

		if (m_szMode == "debug") {
		m_bpDebugging = true;
		}
		
		if (m_bpKeySafe == "1") {
			
			var cswbody = document.getElementsByTagName("body")[0];
			cswbody.setAttribute("onselectstart", "return false;");
			cswbody.setAttribute("ondragstart", "return false");
			cswbody.setAttribute("onmousedown", "if (event.preventDefault){event.preventDefault();}");
			cswbody.setAttribute("onBeforePrint", "document.body.style.display = '';");
			cswbody.setAttribute("onContextmenu", "return false;");
			cswbody.setAttribute("onClick", "if(event.button==2||event.button==3){event.preventDefault();event.stopPropagation();return false;}");
		}
		else {
			var cswbody = document.getElementsByTagName("body")[0];
			cswbody.setAttribute("onselectstart", "return false;");
			cswbody.setAttribute("ondragstart", "return false");
			cswbody.setAttribute("onContextmenu", "return false;");
		}
	 </script>
	 <script src="{$plugin_url}deployJava.js" type="text/javascript"></script>
	 <script src="{$plugin_url}wp-secure-image.js" type="text/javascript"></script>
	 <script type="text/javascript">
		<!-- hide JavaScript from non-JavaScript browsers
		if ((m_szMode == "licensed") || (m_szMode == "debug")) {
			insertSecureImage("$name");
		}
		else {
			document.writeln("<img src='{$plugin_url}images/image_placeholder.jpg' border='0' alt='Demo mode'>");
		}
		// -->
	 </script>
html;
		return $output;
	}

// ============================================================================================================================
# delete short code
function wpsiw_delete_shortcode() {
    // get all posts
    $posts_array = get_posts();
    foreach ( $posts_array as $post ) {
        // delete short code
        $post->post_content = wpsiw_deactivate_shortcode( $post->post_content );
        // update post
        wp_update_post( $post );
    }
}

// ============================================================================================================================
# deactivate short code
function wpsiw_deactivate_shortcode( $content ) {
    // delete short code
    $content = preg_replace( '/\[secimage name="[^"]+"\]\[\/secimage\]/s', '', $content );
	return $content;
}

// ============================================================================================================================
# search short code in post content and get post ids
function wpsiw_search_shortcode( $file_name ) {
    // get all posts
    $posts = get_posts();
    $IDs   = false;
    foreach ( $posts as $post ) {
        $file_name = preg_quote( $file_name,'\\' );
        preg_match( '/\[secimage name="'.$file_name.'"\]\[\/secimage\]/s', $post->post_content, $matches );
        if ( is_array($matches) && isset( $matches[1] ) ) {
            $IDs[] = $post->ID;
        }
    }
    return $IDs;
}


// ============================================================================================================================
# delete file options
function wpsiw_delete_file_options( $file_name ) {
	$file_name = trim($file_name) ;
   	$wpsiw_options = get_option( 'wpsiw_settings' );
   	foreach ($wpsiw_options["classsetting"] as $k => $arr) {
   		if($wpsiw_options["classsetting"][$k][$file_name]){
   			unset($wpsiw_options["classsetting"][$k][$file_name]) ;
   			if( !count($wpsiw_options["classsetting"][$k]) )
   				unset($wpsiw_options["classsetting"][$k]) ;
   		}
   	}
   	update_option( 'wpsiw_settings', $wpsiw_options );
}

// ============================================================================================================================
# install media buttons
function wpsiw_media_buttons ( $context ) {
    global $post_ID;
    // generate token for links
    $token = wp_create_nonce( 'wpsiw_token' );
    $url = admin_url('?wpsiw-popup=file_upload&wpsiw_token='.$token.'&post_id=' . $post_ID);
    return $context.="<a title='Secure Image' href='$url' data-body='no-overflow' class='thickbox' height='auto'><img src='".plugin_dir_url( __FILE__ )."images/secure-image-button.png'></a>";
}


// ============================================================================================================================
# browser detector js file
function wpsiw_load_js() {
    // load custom JS file
    // wp_enqueue_script( 'wpsiw-browser-detector', plugins_url( 'browser_detection.js', __FILE__), array( 'jquery' ) );
	wp_register_script( 'wp-secure-image-uploader', WPSIW_PLUGIN_URL . 'secure-image-media-uploader.js', array( 'jquery', 'plupload-all' ) );
}
function wpsiw_admin_head () {
	$uploader_options = array(
		'runtimes'          => 'html5,silverlight,flash,html4',
		'browse_button'     => 'wpsiw-plugin-uploader-button',
		'container'         => 'wpsiw-plugin-uploader',
		'drop_element'      => 'wpsiw-plugin-uploader',
		'file_data_name'    => 'async-upload',
		'multiple_queues'   => true,
		'max_file_size'     => wp_max_upload_size() . 'b',
		'url'               => admin_url( 'admin-ajax.php' ),
		'flash_swf_url'     => includes_url( 'js/plupload/plupload.flash.swf' ),
		'silverlight_xap_url' => includes_url( 'js/plupload/plupload.silverlight.xap' ),
		'filters'           => array(
			array( 
				'title' => __( 'Allowed Files' ),
				'extensions' => '*'
			)
		),
		'multipart'         => true,
		'urlstream_upload'  => true,
		'multi_selection'   => true,
		'multipart_params' => array(
			'_ajax_nonce' => '',
			'action'      => 'wpsiw-plugin-upload-action'
		)
	);
	?>
    <script type="text/javascript">
        var global_uploader_options=<?php echo json_encode( $uploader_options ); ?>;
    </script>
    <?php
}
add_action( 'admin_head', 'wpsiw_admin_head' );

function wpsiw_ajax_action() {
	add_filter('upload_dir', 'wpsiw_upload_dir');
	// check ajax nonce
	//check_ajax_referer( __FILE__ );

	if( current_user_can( 'upload_files' ) )
	{
		$response = array();
		// handle file upload
		$id = media_handle_upload( 
			'async-upload',
			0, 
			array( 
				'test_form' => true, 
				'action' => 'wpsiw-plugin-upload-action' 
			)
		);

		// send the file' url as response
		if( is_wp_error( $id ) ) {
			$response['status'] = 'error22';
			$response['error'] = $id->get_error_messages();
		} else {
			$response['status'] = 'success';

			$src = wp_get_attachment_image_src( $id, 'thumbnail' );
			$response['attachment'] = array();
			$response['attachment']['id'] = $id;
			$response['attachment']['src'] = $src[0];
		}

	}
	remove_filter('upload_dir', 'wpsiw_upload_dir');
	echo json_encode( $response );
	exit;
}
add_action( 'wp_ajax_wpsiw-plugin-upload-action', 'wpsiw_ajax_action' );

$upload = wp_upload_dir();

function wpsiw_upload_dir($upload) {
	$upload['subdir'] = '/secure-image';
	$upload['path']   = $upload['basedir'] . $upload['subdir'];
	$upload['url']   = $upload['baseurl'] . $upload['subdir'];
	return $upload;
}

// ============================================================================================================================
# admin page scripts
function wpsiw_admin_load_js() {
    // load jquery suggest plugin
    wp_enqueue_script( 'suggest' );
}

// ============================================================================================================================
# admin page styles
function wpsiw_admin_load_styles() {
    // register custom CSS file & load
    wp_register_style( 'wpsiw-style', plugins_url( 'wp-secure-image.css', __FILE__ ) );
	wp_enqueue_style( 'wpsiw-style' );
}

function wpsiw_is_admin_postpage(){
	$chk = false ;
	$ppage_array = explode("/", $_SERVER["SCRIPT_NAME"]) ;
	$ppage = end($ppage_array) ;
	if ($ppage == "post-new.php" || $ppage == "post.php")
		return true;
}
function wpsiw_includecss_js(){
	if (!wpsiw_is_admin_postpage())
		return;
	global $wp_popup_upload_lib ;
	if ($wp_popup_upload_lib)
		return;
	$wp_popup_upload_lib = true ;
	echo "<link rel='stylesheet' href='//code.jquery.com/ui/1.9.2/themes/redmond/jquery-ui.css' type='text/css' />" ;

	// echo "<script type='text/javascript' src='" . WPSIW_PLUGIN_URL . "lib/uploadify/jquery.min.js'></script>" ;
	// echo "<script type='text/javascript' src='" . WPSIW_PLUGIN_URL . "lib/uploadify/jquery.uploadify.min.js'></script>" ;
	// echo "<script type='text/javascript' src='" . WPSIW_PLUGIN_URL . "lib/jquery.json-2.3.js'></script>" ;	
	
	wp_enqueue_script( 'jquery.json', false, array('jquery'));
}

function wpsiw_includecss_js_to_footer() {
	if (!wpsiw_is_admin_postpage())
		return;
	?>
	<script>
	if( jQuery(".thickbox").length > 0 ){
		if( jQuery(".thickbox").data("body") == "no-overflow" ){
			jQuery("body").addClass("wps-no-overflow");
			
		}
	}
	</script>
	<?php
}

// ============================================================================================================================
# setup plugin
function wpsiw_setup () {
	//----add codding---- 
	$options = get_option("wpsiw_settings");

	define( 'WPSIW_PLUGIN_PATH', str_replace("\\", "/", plugin_dir_path(__FILE__) ) ); //use for include files to other files
	define( 'WPSIW_PLUGIN_URL' , plugins_url( '/', __FILE__ ) );
	define( 'WPSIW_UPLOAD_PATH', str_replace("\\", "/", ABSPATH . $options["settings"]["upload_path"] ) ); //use for include files to other files
	define( 'WPSIW_UPLOAD_URL' , site_url( $options["settings"]["upload_path"] ) );
	
	include(WPSIW_PLUGIN_PATH . "login-status.php");
	include(WPSIW_PLUGIN_PATH . "function.php") ;
	
	add_action('admin_head', 'wpsiw_includecss_js') ;
	add_action('admin_footer', 'wpsiw_includecss_js_to_footer') ;

	add_action('wp_ajax_wpsiw_ajaxprocess', 'wpsiw_ajaxprocess' );
	
	//Sanitize the GET input variables
	$pagename = sanitize_key(@$_GET['page']);

	if (!$pagename)
	{
		$pagename = '';
	}
	$siwfilename = sanitize_file_name(@$_GET['siwfilename']);
	if (!$siwfilename)
	{
		$siwfilename = '';
	}
	$action = sanitize_key(@$_GET['action']);
	if (!$action)
	{
		$action = '';
	}
	$siwdel_nonce = sanitize_key(@$_GET['siwdel_nonce']);
	
	if ($pagename == 'wpsiw_list' && $siwfilename && @$_GET['action'] == 'siwdel')
	{
		//check that nonce is valid and user is administrator
		if (current_user_can('administrator') && wp_verify_nonce( @$_GET['siwdel_nonce'], 'siwdel')) 
		{
			echo "Nonce has been verified";
			wpsiw_delete_file_options( $siwfilename );
			if (file_exists(WPSIW_UPLOAD_PATH . $siwfilename))
				unlink(WPSIW_UPLOAD_PATH . $siwfilename);

			wp_redirect( 'admin.php?page=wpsiw_list' ) ;
		}
	}
	
	if( isset($_GET['wpsiw-popup']) && @$_GET["wpsiw-popup"] == "file_upload" ) {
		require_once( WPSIW_PLUGIN_PATH . "popup_load.php" );
		exit();
	}
	//=============================	
	
	// load js file
	add_action( 'wp_enqueue_scripts', 'wpsiw_load_js' );

	// load admin CSS
	add_action( 'admin_print_styles', 'wpsiw_admin_load_styles' );

	// add short code
	add_shortcode( 'secimage', 'wpsiw_shortcode' );

	// if user logged in
	if ( is_user_logged_in() ) {
		// install admin menu
		add_action( 'admin_menu', 'wpsiw_admin_menus' );

		// check user capability
		if ( current_user_can( 'edit_posts' ) ) {
			// load admin JS
			add_action( 'admin_print_scripts', 'wpsiw_admin_load_js' );
			// load media button
			add_action( 'media_buttons_context', 'wpsiw_media_buttons' );
		}
	}

	wp_register_script( 'jquery.json', WPSIW_PLUGIN_URL . 'lib/jquery.json-2.3.js');
}

// ============================================================================================================================
# runs when plugin activated
function wpsiw_activate () {
	// if this is first activation, setup plugin options
	if ( ! get_option( 'wpsiw_settings' ) )
	{
		// set plugin folder
		$upload_dir = 'wp-content/uploads/secure-image/';
		
		// set default options
		$wpsiw_options['settings'] = array(
			'admin_only'    => "checked",
			'upload_path'   => $upload_dir,
			'max_size'      => 400,
			'mode'          => "demo",
			'asps'          => "checked",
			'ie'            => "checked",
			'ff'            => "checked",
			'ch'            => "checked",
			'op'            => "checked",
			'sa'            => "checked"
		);

		update_option( 'wpsiw_settings' , $wpsiw_options );
		
		$upload_dir = ABSPATH . $upload_dir ;
		if (!is_dir($upload_dir))
			mkdir($upload_dir, 0, true);
		// create upload directory if it is not exist
	}
}

// ============================================================================================================================
# runs when plugin deactivated
function wpsiw_deactivate () {
    // remove text editor short code
    // remove_shortcode( 'secimage' );
}

// ============================================================================================================================
# runs when plugin deleted.
function wpsiw_uninstall () {
	// delete all uploaded files
	$upload_dir = wp_upload_dir();
	$user_dirname = $upload_dir['basedir'];
	$default_upload_dir = $user_dirname.'/secure-image/';

	if( is_dir($default_upload_dir) )
	{
		$dir = scandir( $default_upload_dir );
		foreach ( $dir as $file )
		{
			if ( $file != '.' || $file != '..' ) {
				unlink( $default_upload_dir.$file );
			}
		}
		rmdir( $default_upload_dir );
	}

	// delete upload directory
	$options = get_option("wpsiw_settings");
	$upload_path = ABSPATH . $options["settings"]["upload_path"] ;
	
	if( is_dir($upload_path) ){
		$dir = scandir( $upload_path );
		foreach ( $dir as $file ) {
			if ( $file != '.' || $file != '..' ) {
				unlink( $upload_path . '/'.$file );
			}
		}	
		// delete upload directory
		rmdir( $upload_path );
	}
	

	// delete plugin options
	delete_option( 'wpsiw_settings' );

	// unregister short code
	remove_shortcode( 'secimage' );

	// delete short code from post content
	wpsiw_delete_shortcode();
}

// ============================================================================================================================
# register plugin hooks
register_activation_hook( __FILE__, 'wpsiw_activate' ); // run when activated
register_deactivation_hook( __FILE__, 'wpsiw_deactivate' ); // run when deactivated
register_uninstall_hook( __FILE__, 'wpsiw_uninstall' ); // run when uninstalled

add_action( 'init', 'wpsiw_setup' );