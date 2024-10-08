<?php

$ws_domain = 'SocialShopByStoreya';
load_plugin_textdomain($ws_domain, false, dirname( plugin_basename( __FILE__ ) ) . '/facebook-shop-by-storeyacom/');

class woocommerce_storeya_admin {


	private $settings = array();
	private $product_fields = array ();

	function __construct() {

		$this->settings = get_option ( 'woocommerce_storeya_config' );
		$this->product_fields = array (										
									'disable_feed' => array (
										'desc' => __( 'Disable feed', 'woocommerce_storeya' ) ),
		);

		add_action ( 'init', array ( &$this, 'init' ) );

		add_filter ( 'woocommerce_settings_tabs_array', array ( &$this, 'add_woocommerce_settings_tab' ) );
		
		add_action ( 'woocommerce_settings_tabs_storeya', array ( &$this, 'config_page' ) );
		add_action ( 'woocommerce_update_options_storeya', array ( &$this, 'save_settings' ) );	
		
		add_filter('plugin_action_links', array(&$this, 'woocommerce_storeya_plugin_actions'), 10, 2);
	}
	
	function init() {		
		$this->product_fields = apply_filters ( 'woocommerce_wpf_product_fields', $this->product_fields );
		
		
		if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) 
		{
		
		}
		else
		{
			if (function_exists('current_user_can') && current_user_can('manage_options'))
			{               
                  add_action ( 'admin_menu', array ( &$this, 'ws_add_settings_page' ) );
            }
		}
	}

	function add_woocommerce_settings_tab( $tabs ) {
		$tabs['storeya'] = __('Social Store', 'woocommerce_storeya');
		return $tabs;
	}

	function config_page() {

		if ( ! empty ( $_POST['woocommerce_storeya_config'] ) ) {
			$this->save_settings();
		}

?>
        <h3><?php _e ( 'Congratulations!', 'woocommerce_storeya' ); ?></h3>

		
		<p><?php _e ( "You have successfully generated a Products Feed for your store!", 'woocommerce_storeya'); ?></p>
        </p>		
        <p><?php _e ( "1. Please go to ", 'woocommerce_storeya'); ?><a href="<?php esc_attr_e ( "https://www.storeya.com/public/facebook-shop" ); ?>" target="_blank">www.StoreYa.com</a>.</p>
        </p>
		<p><?php _e ( "2. If you are not logged in, please click on the &quot;Get app now&quot; > Type your email address and sign up > Select Woocommerce as your store's solution.", 'woocommerce_storeya'); ?></p>
        </p>
		<p><?php _e ( "3. Type in your Store's URL and click on the &quot;Continue&quot; button and then on the &quot;Activate&quot; button.", 'woocommerce_storeya'); ?></p>
        </p>
		<p><?php _e ( "4. Connect your store to your Facebook fan page.", 'woocommerce_storeya'); ?></p>
        </p>
		<p><?php _e ( "5. Once you are happy with your Facebook store's customization, have it published!", 'woocommerce_storeya'); ?></p>
        </p>
		<br/>
		<p>
		<b><?php _e ( "Settings:", 'woocommerce_storeya'); ?></b></p>
        </p>
		
		<table class="form-table">
			<tr valign="top">
			<th scope="row" class="titledesc">Disable feed</th>
			
			<td class="forminp">
			<div class="woocommerce_storeya_field_selector_group">
			<?php		
				echo '    <input type="checkbox" class="woocommerce_storeya_field_selector" name="woocommerce_storeya_disable_feed" ';
				if ( isset ( $this->settings ) && $this->settings == 'on') {		 
					 echo 'checked="checked"';         
				}	
				echo '>';
			?>
			</td>
			</tr>
        </table>
	    <script type="text/javascript">
		    jQuery(document).ready(function(){
  			    jQuery('.woocommerce_storeya_field_selector').change(function(){
  			    	group = jQuery(this).parent('.woocommerce_storeya_field_selector_group');
                    defspan = group.children('div');
                    defspan.slideToggle('fast');
                });
            });
        </script>
		<?php
	}

	function save_settings() {

		if ( ! wp_verify_nonce( $_REQUEST['_wpnonce'], 'woocommerce-settings' ) ) die( __( 'Action failed. Please refresh the page and retry.', 'woothemes' ) );

		if ( ! $this->settings ) {
			$this->settings = array();
			add_option ( 'woocommerce_storeya_config', $this->settings, '', 'yes' );
		}

		//Sanitize input
		$this->settings = isset( $_POST['woocommerce_storeya_disable_feed']) ? 'on' : null;
		update_option ( 'woocommerce_storeya_config', $this->settings );

	}
	
function woocommerce_storeya_plugin_actions($links, $file)
{ 
    static $this_plugin;
 
    if (!$this_plugin) {
        $this_plugin = plugin_basename(__FILE__);
    } 

    if ($file=="facebook-shop-by-storeyacom/woocommerce-storeya.php" && $this_plugin=="facebook-shop-by-storeyacom/woocommerce-storeya-admin.php") {        
        
        if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
        $settings_link = '<a href="' . get_bloginfo('wpurl') . '/wp-admin/admin.php?page=wc-settings&tab=storeya">Settings</a>';    
        }
        else
        {
        $settings_link = '<a href="' . admin_url('options-general.php?page=facebook-shop-by-storeyacom') . '">' . __('Settings', $ws_domain) . '</a>';
        }
        
        
        array_unshift($links, $settings_link);
    }
    
    return $links;
}






    function ws_add_settings_page()
    {
        function ws_settings_page()
        {
            global $ws_domain;
?>
      <div class="wrap">
        <?php
            screen_icon();
?>
        <h2><?php
            _e("StoreYa Social Shop for WooCommerce ", $ws_domain);
?> 		</h2>
        <div class="metabox-holder meta-box-sortables ui-sortable pointer">
          <div class="postbox" style="float:left;width:30em;margin-right:20px">
            <h3 class="hndle"><span><?php
            _e("StoreYa Social Shop for WooCommerce", $ws_domain);
?></span></h3>
            <div class="inside" style="padding: 0 10px">
            <br/>
		<p>This plugin supports only WooCommerce platform.</p>
		<p>Please state the shopping cart plugin you are currently using and we'll add it to our roadmap. 
		<a href="http://www.storeya.com/public/contactus" target=_blank title="StoreYa.com">Contact us</a></p>

            <br/>
                  </div>
                 </div>

                </div>
              </div>
              <?php
        }
                
        add_submenu_page('options-general.php', __("StoreYa Social Shop for WooCommerce", $ws_domain), __("StoreYa Social Shop for WooCommerce", $ws_domain), 'manage_options', 'facebook-shop-by-storeyacom', 'ws_settings_page');
    }
}

$woocommerce_storeya_admin = new woocommerce_storeya_admin();

?>