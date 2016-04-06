<?php
/*
Plugin Name: Widget Labels
Plugin URI:  https://wordpress.org/plugins/widget-labels/
Description: Allows you to use custom labels/titles for any of your Widgets
Version:     1.0.0
Author:      Maor Chasen
Author URI:  https://generatewp.com/
Text Domain: widget-labels
*/

final class MC_Widget_Labels {
	private static $instance;

	/**
	 * Kickoff
	 *
	 * @static
	 * @since 1.0.0
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
	 * @static
	 * @since 1.0.0
	 */
	public function setup_actions() {
		add_filter( 'widget_update_callback', 	array( $this, 'filter_widget_update_callback' ), 10, 2 );
		add_action( 'in_widget_form', 			array( $this, 'filter_in_widget_form' ), 10, 3 );
		add_action( 'admin_enqueue_scripts', 	array( $this, 'scripts_n_styles' ) );
	}

	/**
	 * Adds form fields to Widget
	 *
	 * @static
	 * @param $widget
	 * @param $return
	 * @param $instance
	 * @return array
	 * @since 1.0.0
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
	 * Updates the Widget with the classes
	 *
	 * @static
	 * @param $instance
	 * @param $new_instance
	 * @return array
	 * @since 1.0.0
	 */
	public function filter_widget_update_callback( $instance, $new_instance ) {
		if ( ! empty( $new_instance['mc_widget_label'] ) ) {
			$instance['mc_widget_label'] = $new_instance['mc_widget_label'];
		} else {
			unset( $instance['mc_widget_label'] );
		}
		
		do_action( 'widget_labels_update', $instance, $new_instance );
		return $instance;
	}

	/**
	 * Enqueues various "scripts n' styles" as I like to call it.
	 *
	 * @since 1.0.0
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