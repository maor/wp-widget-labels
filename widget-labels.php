<?php
/*
Plugin Name: Widget Labels
Plugin URI:  https://wordpress.org/plugins/widget-labels/
Description: Allows you to use custom labels/titles for any of your Widgets
Version:     1.1.0
Author:      Maor Chasen
Author URI:  https://generatewp.com/
Text Domain: widget-labels
*/



/**
 * Security check
 * Prevent direct access to the file.
 *
 * @since 1.1.0
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}



/**
 * Include plugin files
 */
include_once ( plugin_dir_path( __FILE__ ) . 'i18n.php' );



final class MC_Widget_Labels {

	/**
	 * Instance
	 *
	 * The current widget instance's settings.
	 *
	 * @since 1.0.0
	 * @access private
	 * @static
	 * @var array
	 */
	private static $instance;

	/**
	 * Kickoff
	 *
	 * @since 1.0.0
	 * @access public
	 * @static
	 */
	public static function instance() {
		if ( ! isset( self::$instance ) ) {
			self::$instance = new self;
			self::$instance->setup_actions();
		}
		return self::$instance;
	}

	/**
	 * Setup hooks
	 *
	 * Fire the plugin hooks.
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function setup_actions() {
		add_filter( 'widget_update_callback', 	array( $this, 'filter_widget_update_callback' ), 10, 2 );
		add_action( 'in_widget_form', 			array( $this, 'filter_in_widget_form' ), 10, 3 );
		add_action( 'admin_enqueue_scripts', 	array( $this, 'scripts_n_styles' ) );
	}

	/**
	 * Add Fields
	 *
	 * Adds fields to the widget form.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @param WP_Widget $widget   The current widget instance.
	 * @param bool      $return   ?
	 * @param array     $instance The current widget instance's settings.
	 * @return array
	 */
	public function filter_in_widget_form( $widget, $return, $instance ) {
		?>
		<p>
			<label for="<?php echo esc_attr( sprintf( 'widget-%s-%s-mc_widget_label', $widget->id_base, $widget->number ) ); ?>"><?php esc_html_e( 'Widget Label:', 'widget-labels' ); ?></label>
			<input type="text" name="<?php echo esc_attr( "widget-{$widget->id_base}[{$widget->number}][mc_widget_label]" ); ?>" id="<?php echo esc_attr( "widget-{$widget->id_base}-{$widget->number}-mc_widget_label" ); ?>" value="<?php echo esc_attr( $instance['mc_widget_label'] ); ?>" class="widefat">
		</p>
		<?php
	}

	/**
	 * Widget update
	 *
	 * Updates the Widget with the classes.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @param array $old_instance Array of old widget settings.
	 * @param array $new_instance Array of new widget settings.
	 * @return array
	 */
	public function filter_widget_update_callback( $old_instance, $new_instance ) {
		if ( ! empty( $new_instance['mc_widget_label'] ) ) {
			$old_instance['mc_widget_label'] = $new_instance['mc_widget_label'];
		} else {
			unset( $old_instance['mc_widget_label'] );
		}
		
		do_action( 'widget_labels_update', $old_instance, $new_instance );
		return $old_instance;
	}

	/**
	 * Load scripts
	 *
	 * Enqueues various "scripts n' styles" as I like to call it.
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function scripts_n_styles() {
		$screen = get_current_screen();

		// are we on the widgets page?
		if ( 'widgets' == $screen->id ) {
			wp_enqueue_script( 'mc-widget-labels', plugins_url( 'js/widget-labels.js', __FILE__ ), array( 'jquery' ), false, true );
		}
	}
}

function mc_widget_labels() {
	return MC_Widget_Labels::instance();
}
add_action( 'plugins_loaded', 'mc_widget_labels' );