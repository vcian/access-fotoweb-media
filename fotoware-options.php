<?php 
// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit; 
class FotoWeb_Options {

	public $options;
    public $options_name = 'fotoweb_plugin_options';
    public $state;

	public function __construct () { 
	   $this->fw_media_setup_globals();
        $this->setup_hooks();
		add_action('admin_init', array($this, 'register_settings_and_fields'));
        add_action('admin_menu', array($this, 'add_menu_page'));
		$this->options = get_option( $this->options_name);
	}

    public function fw_media_generateState($length = 10) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $state = '';
        for ($i = 0; $i < $length; $i++) {
            $state .= $characters[rand(0, $charactersLength - 1)];
        }
        return $state;
    }

    public function add_menu_page() {
		add_menu_page('FotoWare Plugin', 'FotoWare Settings', 'manage_options', 'fotoweb_plugin', array($this, 'fw_media_display_options_page'), '
        dashicons-admin-generic', 110);
	}

	public function fw_media_display_options_page () {
		?>
			<div class="wrap fotoware-setting">
				<div class="fotoware-setting-form">
					<form method="post" action="options.php">
						<?php 
							settings_fields($this->options_name);
							do_settings_sections(__FILE__); 
							submit_button();
						?>
					</form>
				</div>
			</div>
		<?php
	}
	
	public function register_settings_and_fields () {
		register_setting($this->options_name, $this->options_name, array($this, 'fotoweb_validate_settings')); 
		add_settings_section( 'fw_main_section', 'FotoWare Settings', array($this, 'fw_main_section_cb'), __FILE__ );
		add_settings_field( 'fw_url', 'FotoWare URL', array($this, 'fw_url_setting'), __FILE__, 'fw_main_section');
		add_settings_field( 'client_id', 'Client ID', array($this, 'client_id_setting'), __FILE__, 'fw_main_section');
		add_settings_field( 'client_secret', 'Client Secret', array($this, 'client_secret_setting'), __FILE__, 'fw_main_section');
		add_settings_field( 'redirect_uri', 'Redirect URI', array($this, 'redirect_uri_setting'), __FILE__, 'fw_main_section');
		add_settings_field( 'logout', '', array($this, 'fotoweb_logout_settings'), __FILE__, 'fw_main_section');

	}


	public function fotoweb_validate_settings ($plugin_options) {
		return $plugin_options;
	}


	/*
	*
	* INPUTS
	*
	*/
	public function fw_main_section_cb () {
		echo "Add below details and save settings";
	}
	public function fw_url_setting () {
		echo "<input name='$this->options_name[fw_url]' type='text' value='{$this->options['fw_url']}' />";
	}
	public function client_id_setting () {
		echo "<input name='$this->options_name[client_id]' type='text' value='{$this->options['client_id']}' />";
	}
	public function client_secret_setting () {
		echo "<input name='$this->options_name[client_secret]' type='text' value='{$this->options['client_secret']}' />";
	}
	public function wordpress_url_setting () {
		echo "<input name='$this->options_name[wordpress_url]' type='text' value='{$this->options['wordpress_url']}' />";
	}

	public function redirect_uri_setting () {
		$fotoware_base_uri = get_site_url();
		echo '<div> '.FW_MEDIA_AC_URL.'callback.php</div>'; 
	}
	public function fotoweb_logout_settings(){
			echo '<a href="'.FW_MEDIA_AC_URL.'fotoware-logout.php" class="button">Log out From FotoWare</a>'; 
	}
	private function fw_media_setup_globals() {
        // let's define some globals to easily build the url to your scripts
        $this->version      = '1.0.0';
        $this->file         = __FILE__;
 
        // url to your plugin dir : site.url/wp-content/wp-fotoweb-api/
        $this->plugin_url   = plugin_dir_url( $this->file );
 
        // url to your plugin's includes dir : site.url/wp-content/plugins/wp-fotoweb-api/includes/
        $this->includes_url = trailingslashit( $this->plugin_url . 'includes' );
 
        // url to your plugin's js dir : site.url/wp-content/plugins/wp-fotoweb-api/includes/js/
        $this->plugin_js    = trailingslashit( $this->includes_url . 'js' );
 
        // url to your plugin's css dir : site.url/wp-content/plugins/wp-fotoweb-api/includes/css/
        $this->plugin_css   = trailingslashit( $this->includes_url . 'css' );
 
        $this->component_id   = 'wp-fotoweb-api';
        $this->component_slug = 'wp-fotoweb-api';
    }
 
    private function setup_hooks() {
        // As soon as WordPress meets this hook, your cssjs function will be called
        add_action( 'admin_enqueue_scripts', array( $this, 'fw_media_include' ) ); 
    }
 
    public function fw_media_include() {
        // Your css file is reachable at site.url/wp-content/plugins/buddyplug/includes/css/buddyplug.css
        wp_enqueue_style( 'fotoware-media', FW_MEDIA_AC_URL . 'css/fotoware-media.css', false, $this->version );
    }
}
?>
