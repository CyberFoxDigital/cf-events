<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://cyberfoxdigital.co.uk
 * @since      1.0.0
 *
 * @package    Cf_Events
 * @subpackage Cf_Events/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Cf_Events
 * @subpackage Cf_Events/admin
 * @author     Adam Lanning-Molyneux <info@cyberfoxdigital.co.uk>
 */
class Cf_Events_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version,  $custom_fields ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

		$this->custom_fields = $custom_fields;
		
		$this->notices();
		$this->post_custom_fields();

	}
	
	/**
	 * Display warning notices to the admin user
	 *
	 * @since    1.0.0
	 */
	public function notices(){
		//Harass about CMB
		add_action( 'admin_init', function(){
			//Init CMB
			if (is_admin() && !is_plugin_active('cmb2/init.php') ) {
				add_action( 'admin_notices', function(){
					echo '<div class="error"><p>You must install <a href="https://github.com/WebDevStudios/CMB2/">CMB MetaBoxes 2</a> to make full use of the Shady Fox Rooms Plugin</p></div>';
				}); 
			}
		});
	}
	
	/**
	 * Register custom meta fields for the custom post types
	 *
	 * @since    1.0.0
	 */
	public function post_custom_fields(){
		//Metaboxes
		add_filter( 'cmb2_meta_boxes', function($metaboxes) {
			$custom_post_meta_field_array = array();
			
			if(empty($metaboxes) || !is_array($metaboxes)) {
				$metaboxes = array();
			}
			
			foreach($this->custom_fields as $id => $field){
				$field['id'] 						= $id;
				$field['object_types'] 	= array('customer');
				$custom_post_meta_field_array[$id] = $field;
			}
			
			
			if(!empty($custom_post_meta_field_array)) {
				$metaboxes[] = array(
					'id' 						=> 'event',
					'title' 				=> __( 'Event Information', $this->plugin_name ),
					'object_types' 	=> array('event'), //post type
					'context' 			=> 'normal',
					'priority' 			=> 'high',
					'show_names' 		=> true, // Show field names on the left
					'fields' 				=> $custom_post_meta_field_array
				);
			} 
			return $metaboxes;
		});
	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Cf_Events_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Cf_Events_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/cf-events-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Cf_Events_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Cf_Events_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/cf-events-admin.js', array( 'jquery' ), $this->version, false );

	}

}
