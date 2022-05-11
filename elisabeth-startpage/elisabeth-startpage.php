<?php
 
/*
 
Plugin Name: Elisabeth Startsida
Plugin URI: https://kreativapsykologen.se
Description: Plugin som gör att man kan ändra på texterna på startsidan!
Version: 1.0
Author: Erik Lissel
Author URI: https://lissel.net
License: MIT
Text Domain: idk
 
*/

global $elisabeth_db_version;
$elisabeth_db_version = "1.0";

function tbl_create() {
   // trigger_error("tbl_create", E_USER_ERROR);
    global $wpdb;
    global $elisabeth_db_version;

    $table_name = $wpdb->prefix . "elisabeth_startpage";
    $charset_collate = $wpdb->get_charset_collate();

    $sql = "CREATE TABLE `$table_name` (
        id mediumint(9) NOT NULL,
        description VARCHAR(10) NOT NULL,
        text text NOT NULL,
        PRIMARY KEY  (id)
      ) $charset_collate;";
      
      require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
      dbDelta( $sql );

      add_option("elisabeth_db_version", $elisabeth_db_version);
}

function tbl_seed() {
    global $wpdb;

  //  trigger_error("tbl_seed", E_USER_ERROR);

    $table_name = $wpdb->prefix . 'elisabeth_startpage';

    $wpdb->insert( 
        $table_name,
        array( 
            'id' => 1,
            'description' => "aktuellt",
            'text' => "Utsällning i Wadköping i sommar!",
        ) 
    );

    $wpdb->insert( 
        $table_name, 
        array( 
            'id' => 2,
            'description' => "om mig",
            'text' => "Jag har gjort en massa roliga saker!",
        ) 
    );
}

function plugin_menu() {
//	trigger_error('<<<<< plugin_menu >>>>>', E_USER_ERROR);
	add_menu_page('Startsidan', 'Startsidan', 'manage_options', 'elisabeth-startpage', 'menu_init');
}

function menu_init() {
	$has_saved = handle_post();
	global $wpdb;

	$table_name = $wpdb->prefix . "elisabeth_startpage";

	$sql = "SELECT * FROM `$table_name`";
	$posts = $wpdb->get_results($sql);
	$aktuellt = "";
	$om_mig = "";
	foreach($posts as $post) {
		if($post->description == "aktuellt") {
			$aktuellt = $post->text;
		}
		if($post->description == "om mig") {
			$om_mig = $post->text;
		}
	}
?>
	<h1>Inställningar för startsidan</h1>
	<p>Se till att stycken är omgivna taggar: &lt;p&gt;Hej jag är ett stycke&lt;/p&gt;</p>
	<p>En lista görs med: &lt;ul&gt;&lt;li&gt;Item 1&lt;/li&gt;&lt;li&gt;Item 2&lt;/li&gt;&lt;/ul&gt;</p>
<?php
	if($has_saved) {
		echo('<p id="startsida_notice" style="font-weight: bold; color: #33bb33; font-size: 24px">Sparat :)</p>');
		echo('<script>setTimeout(() => { document.querySelector("#startsida_notice").style.display = "none"; }, 4000);</script>');
	}
?>
	<form method="post">
		<h2>Aktuellt</h2>
		<textarea
			name="startsida_aktuellt"
			id="startsida_aktuellt"
			style="width: 100%; height: 250px"
		><?php echo($aktuellt) ?></textarea>

		<h2>Om mig</h2>
		<textarea 
			name="startsida_om_mig"
			id="startsida_om_mig"
			style="width: 100%; height: 250px"
		><?php echo($om_mig) ?></textarea>

		<br>
		<div style="text-align: center; margin-top: 16px;">
			<input
				type="submit"
				value="spara"
				style="font-size: 24px; background-color: #2abb2a; color: white; padding: 5px; border: 1px; border-radius: 5px;"
			 />
		</div>
	</form>
<?php
}

function handle_post() {
	$change = false;
	if(isset($_POST["startsida_aktuellt"])) {
		$aktuellt = $_POST["startsida_aktuellt"];
		global $wpdb;

		$table_name = $wpdb->prefix . 'elisabeth_startpage';

		$wpdb->replace(
			$table_name,
			array(
				'id' => 1,
				'description' => "aktuellt",
				'text' => $aktuellt,
			)
		);
		$change = true;
	}
	
	if(isset($_POST["startsida_om_mig"])) {
		$om_mig = $_POST["startsida_om_mig"];
		global $wpdb;

		$table_name = $wpdb->prefix . 'elisabeth_startpage';

		$wpdb->replace(
			$table_name,
			array(
				'id' => 2,
				'description' => "om mig",
				'text' => $om_mig,
			)
		);
		$change = true;
	}
	return $change;
}

function is_home_page() {
	// This is not great. Maybe try and read home_url and do regexp tricks to determine is WP is behind a folder.
	$path = $_SERVER['REQUEST_URI'];
	if($path == "/" || $path == "/index.php" || $path == "/wordpress" || $path == "/wordpress/" || $path == "/wordpress/index.php") {
		return true;
	}
	return false;
}

function redirect_startpage() {
	// https://wordpress.stackexchange.com/questions/237659/check-if-i-am-in-the-admin-panel-wp-admin/237758
	$is_normal_page =  !is_admin() && ( !defined( 'DOING_AJAX' ) || !DOING_AJAX ) && !is_wplogin(); 
	// Extra safety, make sure we don't accidentally override an admin page or the login.
	if ($is_normal_page) {
		if(is_home_page()) {
			include("front-page.php");
			exit;
		}
	}
}

function is_wplogin(){
    $ABSPATH_MY = str_replace(array('\\','/'), DIRECTORY_SEPARATOR, ABSPATH);
    return ((in_array($ABSPATH_MY.'wp-login.php', get_included_files()) || in_array($ABSPATH_MY.'wp-register.php', get_included_files()) ) || (isset($_GLOBALS['pagenow']) && $GLOBALS['pagenow'] === 'wp-login.php') || $_SERVER['PHP_SELF']== '/wp-login.php');
}


add_action('admin_menu', 'plugin_menu');

register_activation_hook(__FILE__, "tbl_create");
register_activation_hook(__FILE__, "tbl_seed");

// =================== CONTENT ========================

redirect_startpage();

?>
