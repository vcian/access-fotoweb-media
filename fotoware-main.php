<?php 
// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit; 
if( !class_exists('FotoWeb')){

    function fw_media_generateState($length = 10) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $state = '';
        for ($i = 0; $i < $length; $i++) {
            $state .= $characters[rand(0, $charactersLength - 1)];
        }
        return $state;
    }

    $_SESSION['state'] = fw_media_generateState();

    class FotoWeb {
        
        public $plugin;
        public $fw_options;
        public $logged_in;

        function __construct () {
            $this->fw_options = new FotoWeb_Options();
            $this->plugin = plugin_basename(__FILE__);
            
            global $fotoweb_url;
            $fotoweb_url = $this->fw_options->options['fw_url'];
            $_SESSION['fw_url'] = $this->fw_options->options['fw_url'];
            $_SESSION['client_id'] = $this->fw_options->options['client_id'];
            $_SESSION['client_secret'] = $this->fw_options->options['client_secret'];
            $_SESSION['redirect_uri'] =  FW_MEDIA_AC_URL.'callback.php';
			$_SESSION['afterlog_url'] =  FW_MEDIA_AC_URL;
        }

        function fw_media_register_script(){
            add_filter("plugin_action_links_$this->plugin" , array($this, 'settings_link'));

            if($this->fw_options->options['fw_url']) {
                add_action('media_buttons', array($this,'login_fotoweb_button'), 15);
            }
        }
      
        public function settings_link( $links){
            $settings_link = '<a href=admin.php?page=fotoweb_plugin>Settings</a>';
            array_push($links, $settings_link);
            return $links;
        }

        public function login_fotoweb_button(){
             echo '<a  onclick="handleOpenWindow
             
        ()" target="_blank"   id="fotoweb_button" class="button"><img src="'. FW_MEDIA_AC_IMG_URL.'/fotoweb_icon.png" style="vertical-align:sub; padding-right: 4px; padding-left: 0px;" height="16px" width="16px"/>Add Media from FotoWare</a>';		
        ?>
		<script>
           const fw_url = '<?php echo $_SESSION['fw_url'];?>'; 
		    const afterlog_url = '<?php echo $_SESSION['afterlog_url'];?>'; 
		    function handleOpenWindow(){
			const access_token = '<?php echo $_SESSION['access_token']?>';
			const client_id = '<?php echo $_SESSION['client_id']?>';
			const redirect_uri = '<?php echo $_SESSION['redirect_uri']?>';
			const state = '<?php echo $_SESSION['state']?>';

			const isLoggedIn = window.localStorage.getItem('isLoggedIn');
			if(isLoggedIn === 'true'){
				const token = '<?php echo fw_media_get_access_token_from_refresh_token() ?>';
				showcontactusform();
			}else{
				window.open(fw_url + "/fotoweb/oauth2/authorize?response_type=code&client_id="+client_id+"&redirect_uri="+redirect_uri+"&state="+state);
			}
		}
		function showcontactusform() {
			  //set the width and height of the 
			  //pop up window in pixels
			  var width = 655;
			  var height = 555;
		  
			  //Get the TOP coordinate by
			  //getting the 50% of the screen height minus
			  //the 50% of the pop up window height
			  var top = parseInt((screen.availHeight/2) - (height/2));
		  
			  //Get the LEFT coordinate by
			  //getting the 50% of the screen width minus
			  //the 50% of the pop up window width
			  var left = parseInt((screen.availWidth/2) - (width/2));
		  
			  //Open the window with the 
			  //file to show on the pop up window
			  //title of the pop up
			  //and other parameter where we will use the
			  //values of the variables above
			  window.open(afterlog_url +"fotoware-selection.php", 
					"", 
					"menubar=no,resizable=no,width=655,height=555,scrollbars=0,left="  
					+ left + ",top=" + top + ",screenX=" + left + ",screenY=" + top);    
			  }

		const popup = () =>  {
			newwindow=window.open(afterlog_url +"fotoware-selection.php",'FotoWeb','fullscreen=1,scrollbars=0,resizable=0,left=0, top=0,width=655,height=555');
			window.focus && newwindow.focus()
		}
		window.addEventListener("focus", function(event) {
			const exported_url = window.localStorage.getItem('exported_url');
			if(exported_url){
				wp.media.editor.insert(exported_url);
				window.localStorage.removeItem('exported_url');
			}	
		}, false);

		window.addEventListener('storage', function(e) {  
		 if(e.newValue === 'true'){
			 popup();
		 }
		});</script>
		<?php } 

        public function fw_media_registrer_my_scripts(){
            wp_localize_script( 'fw-javascript', 'fwParams', $this->fw_options->options );
        }
 }
$fotoweb = new FotoWeb();
$fotoweb -> fw_media_register_script();
} ?>
