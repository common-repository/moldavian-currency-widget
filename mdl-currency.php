<?php
/**
 * @package   Moldavian Currency Widget
 * @author    Igor Kukuler <ikukuler@gmail.com>
 * @license   GPL-2.0+
 * @link      http://igorkukuler.net
 * @copyright 2017 Igor Kukuler
 *
 * @wordpress-plugin
 * Plugin Name:       Moldavian Currency Widget
 * Plugin URI:        https://wordpress.org/plugins/moldavian-currency-widget/
 * Description:       Widget with oficial exchange rates of Banca Nationala a Moldovei.
 * Version:           1.0.0
 * Author:            Xelens
 * Author URI:        http://igorkukuler.net
 * Text Domain:       mdl-currency
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Domain Path:       /lang
 */
 
 // Prevent direct file access
if ( ! defined ( 'ABSPATH' ) ) {
	exit;
}

// creating widget
class MDL_Currency_Widget extends WP_Widget {

    /**
     *
     * Unique identifier for widget.
     *
     *
     * @since    1.0.0
     *
     * @var      string
     */
    protected $widget_slug = 'mdl-currency';

	/*--------------------------------------------------*/
	/* Constructor
	/*--------------------------------------------------*/
	public function __construct() {

		// load plugin text domain
		add_action( 'plugins_loaded', 'mdlc_textdomain' );		

		parent::__construct(
			$this->get_widget_slug(),
			__( 'Moldavian Currency Widget', $this->get_widget_slug() ),
			array(
				'classname'  => $this->get_widget_slug().'-class',
				'description' => __( 'Displays exchange rates of BNM', $this->get_widget_slug() )
			)
		);

		// Register admin styles and scripts
		add_action( 'admin_print_styles', array( $this, 'register_mdlc_admin_styles' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'register_mdlc_admin_scripts' ) );

		// Register frontend styles and scripts
		add_action( 'wp_enqueue_scripts', array( $this, 'register_mdlc_widget_styles' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'register_mdlc_widget_scripts' ) );

	} // end constructor


    /**
     * Return the widget slug.
     *
     * @since    1.0.0
     *
     * @return    Plugin slug variable.
     */
    public function get_widget_slug() {
        return $this->widget_slug;
    }

    /**
    * Gets data from official site of Banca Nationala a Moldovei
    * 
    * @since 	1.0.0
    *
    * @param 	string lang The string with language selected by user
    *
    * @return 	Array of all rates
    */

    private function get_rates_bnm( $lang = 'en' ) {
		$date = date('d.m.Y');
		$url = 'http://www.bnm.md/' . $lang . '/official_exchange_rates?get_xml=1&date=' . $date;
		$response = wp_remote_get( $url, array('timeout' => 5) );
		$body = wp_remote_retrieve_body( $response );
		$xml = simplexml_load_string( $body );
		$json = json_encode( $xml );
		$data = json_decode( $json, true );
		return $data['Valute'];
	}

	/*--------------------------------------------------*/
	/* Widget API Functions
	/*--------------------------------------------------*/

	/**
	 * Outputs the content of the widget.
	 *
	 * @param array args  The array of form elements
	 * @param array instance The current instance of the widget
	 */
	public function widget( $args, $instance ) {

		if ( ! empty( $instance ) ) {
			$title = $instance['title'];
		} 
		$currencies = $instance['currencies'];
		$rates_lang = $instance['lang'];

		$bnm_rates = $this->get_rates_bnm( $rates_lang );


		extract( $args, EXTR_SKIP );

		$widget_string = $before_widget;

		ob_start();
		include( plugin_dir_path( __FILE__ ) . 'views/widget.php' );
		$widget_string .= ob_get_clean();
		$widget_string .= $after_widget;


		print $widget_string;

	} // end widget
	
	
	/**
	 * Processes the widget's options to be saved.
	 *
	 * @param array new_instance The new instance of values to be generated via the update.
	 * @param array old_instance The previous instance of values before the update.
	 */
	public function update( $new_instance, $old_instance ) {

		$instance = $old_instance;

		if ( ! empty( $new_instance['title'] ) ) {
			$instance['title'] = htmlentities($new_instance['title']);
		}

		if ( ! empty( $new_instance['currencies'] ) ) {
			$instance['currencies'] = $new_instance['currencies'];
		}

		if ( ! empty( $new_instance['lang'] ) ) {
			$instance['lang'] = $new_instance['lang'];
		}

		return $instance;

	} // end update

	/**
	 * Generates the administration form for the widget.
	 *
	 * @param array instance The array of keys and values for the widget.
	 */
	public function form( $instance ) {

		$defaults = array(
			'title' => 'Exchange Rates BNM',
			'currencies' => array('978'), // Number code of Euro
			'lang' => 'en'
		);
		
		$instance = wp_parse_args( (array) $instance, $defaults );

		$bnm_rates = $this->get_rates_bnm();

		$title = $instance['title'];
		$currencies = $instance['currencies'];
		$rates_lang = $instance['lang'];

		$mdl_title_id = $this->get_field_id('title');
		$mdl_title_name = $this->get_field_name('title');
		$mdl_currencies_id = $this->get_field_id('currencies');
		$mdl_currencies_name = $this->get_field_name('currencies');
		$mdl_lang_id = $this->get_field_id('lang');
		$mdl_lang_name = $this->get_field_name('lang');
		

		// Display the admin form
		include( plugin_dir_path(__FILE__) . 'views/admin.php' );

	} // end form

	/*--------------------------------------------------*/
	/* Public Functions
	/*--------------------------------------------------*/

	/**
	 * Loads the Widget's text domain for localization and translation.
	 */
	public function mdlc_textdomain() {

		load_plugin_textdomain( $this->get_widget_slug(), false, plugin_dir_path( __FILE__ ) . 'lang/' );

	} // end widget_textdomain

	/**
	 * Registers and enqueues admin-specific styles.
	 */
	public function register_mdlc_admin_styles() {

		wp_enqueue_style( $this->get_widget_slug().'-admin-styles', plugins_url( 'css/admin.css', __FILE__ ) );
		wp_enqueue_style( $this->get_widget_slug().'-bs-select-admin-styles', plugins_url( 'css/bootstrap-select.min.css', __FILE__ ) );

	} // end register_admin_styles

	/**
	 * Registers and enqueues admin-specific JavaScript.
	 */
	public function register_mdlc_admin_scripts() {

		wp_enqueue_script( $this->get_widget_slug().'-admin-script', plugins_url( 'js/admin.js', __FILE__ ), array('jquery') );
		wp_enqueue_script( $this->get_widget_slug().'-bs-admin-script', plugins_url( 'js/bootstrap.min.js', __FILE__ ), array('jquery') );
		wp_enqueue_script( $this->get_widget_slug().'-bs-select-admin-script', plugins_url( 'js/bootstrap-select.min.js', __FILE__ ), array('jquery') );

	} // end register_admin_scripts

	/**
	 * Registers and enqueues widget-specific styles.
	 */
	public function register_mdlc_widget_styles() {

		wp_enqueue_style( $this->get_widget_slug().'-widget-styles', plugins_url( 'css/widget.css', __FILE__ ) );
		wp_enqueue_style( 'dashicons-style', get_stylesheet_uri(), array('dashicons'), '1.0' );

	} // end register_widget_styles

	/**
	 * Registers and enqueues widget-specific scripts.
	 */
	public function register_mdlc_widget_scripts() {

		wp_enqueue_script( $this->get_widget_slug().'-script', plugins_url( 'js/widget.js', __FILE__ ), array('jquery') );

	} // end register_widget_scripts

} // end class


add_action( 'widgets_init', create_function( '', 'register_widget("MDL_Currency_Widget");' ) );
