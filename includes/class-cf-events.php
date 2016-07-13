<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://cyberfoxdigital.co.uk
 * @since      1.0.0
 *
 * @package    Cf_Events
 * @subpackage Cf_Events/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Cf_Events
 * @subpackage Cf_Events/includes
 * @author     Adam Lanning-Molyneux <info@cyberfoxdigital.co.uk>
 */
class Cf_Events {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Cf_Events_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {

		$this->plugin_name = 'cf-events';
		$this->version = '1.0.0';
		
		$this->custom_fields = array(
			'eventType' 	=> array(
				'name'    			=> __( 'Event Type', $this->plugin_name ),
				'type'    			=> 'select',
				'searchable'		=> TRUE,
				'options'				=> array(
					'BusinessEvent' 		=> __('Business Event', $this->plugin_name ),
					'ChildrensEvent' 		=> __('Childrens Event', $this->plugin_name ),
					'ComedyEvent' 			=> __('Comedy Event', $this->plugin_name ),
					'DanceEvent' 				=> __('Dance Event', $this->plugin_name ),
					'DeliveryEvent' 		=> __('Delivery Event', $this->plugin_name ),
					'EducationEvent' 		=> __('Education Event', $this->plugin_name ),
					'ExhibitionEvent' 	=> __('Exhibition Event', $this->plugin_name ),
					'Festival' 					=> __('Festival', $this->plugin_name ),
					'FoodEvent' 				=> __('Food Event', $this->plugin_name ),
					'LiteraryEvent' 		=> __('Literary Event', $this->plugin_name ),
					'MusicEvent' 				=> __('Music Event', $this->plugin_name ),
					'PublicationEvent' 	=> __('Publication Event', $this->plugin_name ),
					'SaleEvent' 				=> __('Sale Event', $this->plugin_name ),
					'ScreeningEvent' 		=> __('Screening Event', $this->plugin_name ),
					'SocialEvent' 			=> __('Social Event', $this->plugin_name ),
					'SportsEvent' 			=> __('Sports Event', $this->plugin_name ),
					'TheaterEvent' 			=> __('Theater Event', $this->plugin_name ),
					'VisualArtsEvent' 	=> __('Visual Arts Event', $this->plugin_name )
				)
			),
			'date' 	=> array(
				'name'    			=> __( 'Date', $this->plugin_name ),
				'type'    			=> 'text_datetime_timestamp',
				'searchable'		=> TRUE
			),
			'venue' 	=> array(
				'name'    			=> __( 'Venue Name', $this->plugin_name ),
				'type'    			=> 'text',
				'searchable'		=> TRUE
			),
			'address' 	=> array(
				'name'    			=> __( 'Venue Address', $this->plugin_name ),
				'type'    			=> 'text',
				'searchable'		=> TRUE
			),
			'price' 	=> array(
				'name'    			=> __( 'Entry fee', $this->plugin_name ),
				'type'    			=> 'text',
				'searchable'		=> TRUE
			),
			'tickets' 	=> array(
				'name'    			=> __( 'Tickets URL', $this->plugin_name ),
				'type'    			=> 'text',
				'searchable'		=> FALSE
			)
		);
		
		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();

		$this->register_post_types();
		$this->register_taxonomies();
	}

	/**
	 * Register custom post type
	 *
	 * @since    1.0.0
	 */
	private function register_post_types(){
		add_action( 'init', function(){
			$labels = array(
				'name'               => _x( 'Event', 'post type general name', 'cyberfox' ),
				'singular_name'      => _x( 'Event', 'post type singular name', 'cyberfox' ),
				'menu_name'          => _x( 'Events', 'admin menu', 'cyberfox' ),
				'name_admin_bar'     => _x( 'Event', 'add new on admin bar', 'cyberfox' ),
				'add_new'            => _x( 'Add New', 'customer', 'cyberfox' ),
				'add_new_item'       => __( 'Add New Event', 'cyberfox' ),
				'new_item'           => __( 'New Event', 'cyberfox' ),
				'edit_item'          => __( 'Edit Event', 'cyberfox' ),
				'view_item'          => __( 'View Evenst', 'cyberfox' ),
				'all_items'          => __( 'All Events', 'cyberfox' ),
				'search_items'       => __( 'Search Events', 'cyberfox' ),
				'parent_item_colon'  => __( 'Parent Event:', 'cyberfox' ),
				'not_found'          => __( 'No events found.', 'cyberfox' ),
				'not_found_in_trash' => __( 'No events found in Trash.', 'cyberfox' )
			);
		
			$args = array(
				'labels'             => $labels,
				'description'        => __( 'Cyber Fox Event', 'cyberfox' ),
				'public'             => true,
				'publicly_queryable' => true,
				'show_ui'            => true,
				'show_in_menu'       => true,
				'query_var'          => true,
				'rewrite'            => array( 'slug' => 'events' ),
				'capability_type'    => 'post',
				'has_archive'        => true,
				'hierarchical'       => false,
				'menu_position'      => NULL,
				'menu_icon'					 => 'dashicons-calendar',
				'supports'           => array( 'title', 'editor', 'excerpt', 'thumbnail')
			);
		
			register_post_type( 'event', $args );
		});
	}
	

	/**
	 * Register custom taxonomies
	 *
	 * @since    1.0.0
	 */
	private function register_taxonomies(){
		
	}
	
	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Cf_Events_Loader. Orchestrates the hooks of the plugin.
	 * - Cf_Events_i18n. Defines internationalization functionality.
	 * - Cf_Events_Admin. Defines all hooks for the admin area.
	 * - Cf_Events_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-cf-events-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-cf-events-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-cf-events-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-cf-events-public.php';

		$this->loader = new Cf_Events_Loader();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Cf_Events_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new Cf_Events_i18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {

		$plugin_admin = new Cf_Events_Admin( $this->get_plugin_name(), $this->get_version(), $this->get_custom_fields()  );


		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );

	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

		$plugin_public = new Cf_Events_Public( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );

	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    Cf_Events_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}
	
	/**
	 * Retrieve the custom fields of the post type.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_custom_fields() {
		return $this->custom_fields;
	}

}
