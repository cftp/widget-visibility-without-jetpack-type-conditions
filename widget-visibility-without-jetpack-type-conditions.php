<?php

/*
 * Plugin Name: Widget Visibility Without Jetpack – Type Conditions
 * Plugin URI: https://github.com/cftp/widget-visibility-without-jetpack-type-conditions
 * Description: Builds on Widget Visibility Without Jetpack to provide additional conditions
 * Author: cftp, simonwheatley
 * Version: 0.1
 * License: GPL2+
*/


/**
 * 
 * 
 * @package 
 **/
class WVWJPTC {

	/**
	 * A version integer.
	 *
	 * @var int
	 **/
	var $version;

	/**
	 * Singleton stuff.
	 * 
	 * @access @static
	 * 
	 * @return WVWJPTC object
	 */
	static public function init() {
		static $instance = false;

		if ( ! $instance )
			$instance = new WVWJPTC;

		return $instance;

	}

	/**
	 * Class constructor
	 *
	 * @return null
	 */
	public function __construct() {
		add_action( 'widget_conditions_before_admin_form', array( $this, 'action_widget_conditions_before_admin_form' ), 10, 3 );
		add_filter( 'widget_conditions_condition_result', array( $this, 'filter_widget_conditions_condition_result' ), 10, 2 );
		add_filter( 'widget_conditions_update', array( $this, 'filter_widget_conditions_update' ), 10, 4 );
	}

	// HOOKS
	// =====

	/**
	 * Hooks the Widget Conditions action widget_conditions_before_admin_form
	 * Adds the additional type condition UI.
	 *
	 * @action widget_conditions_before_admin_form
	 * 
	 * @param array $instance
	 * @param $return not used
	 * @param $widget not used
	 *
	 * @return void
	 * @author Simon Wheatley
	 **/
	public function action_widget_conditions_before_admin_form( $instance, $return, $widget ) {
		$type = '';
		if ( isset( $instance['conditions']['type'] ) )
			$type = $instance['conditions']['type'];

		?>
		<div class="condition">
			<div class="alignleft">
				<select class="conditions-rule-type" name="conditions[type]">
					<option value="" <?php selected( "", $type ); ?>><?php echo esc_html_x( 'on any type (i.e. everywhere)', 'Used as the default option in a dropdown list', 'jetpack' ); ?></option>
					<option value="is_front_page" <?php selected( "is_front_page", $type ); ?>><?php esc_html_e( 'on the front page only', 'jetpack' ); ?></option>
					<option value="is_home" <?php selected( "is_home", $type ); ?>><?php esc_html_e( 'on the news page only', 'jetpack' ); ?></option>
					<option value="is_archive" <?php selected( "is_archive", $type ); ?>><?php esc_html_e( 'on an archive page only', 'jetpack' ); ?></option>
					<option value="is_singular" <?php selected( "is_singular", $type ); ?>><?php esc_html_e( 'on single posts (or pages, etc) only', 'jetpack' ); ?></option>
				</select>
			</div>
			<span class="condition-conjunction"><?php echo esc_html_x( 'and', 'Shown between widget visibility conditions.', 'jetpack' ); ?></span>
			<br class="clear" />
		</div><!-- .condition -->
		<?php
	}

	/**
	 * Hooks the Widget Conditions filter widget_conditions_update
	 * Save the type condition on the widget instance
	 *
	 * @filter widget_conditions_update
	 * 
	 * @param array $conditions The current widget conditions, to be returned
	 * @param array $instance The current widget settings instance
	 * @param array $new_instance The new widget settings as was
	 * @param array $old_instance The old widget settings as were
	 *
	 * @return void
	 * @author Simon Wheatley
	 **/
	public function filter_widget_conditions_update( $conditions, $instance, $new_instance, $old_instance ) {
		
		$conditions['type'] = '';
		
		if ( isset( $_POST['conditions']['type'] ) ) {
			$conditions['type'] = $_POST['conditions']['type'];
			$conditions[ 'active' ] = true;
		}

		return $conditions;
	}

	/**
	 * Hooks the Widget Conditions filter widget_conditions_before_admin_form
	 * Checks the additional `type` condition allows widget display.
	 *
	 * @filter widget_conditions_condition_result
	 * 
	 * @param bool $condition_result Whether to show the widget
	 * @param array $instance The widget settings for this instance
	 *
	 * @return void
	 * @author Simon Wheatley
	 **/
	public function filter_widget_conditions_condition_result( $condition_result, $instance ) {
		
		if ( $instance['conditions']['type'] ) {
			switch ( $instance['conditions']['type'] ) {
				case 'is_front_page':
					$condition_result = is_front_page();
				break;
				case 'is_singular':
					$condition_result = is_singular();
				break;
				case 'is_home':
					$condition_result = is_home();
				break;
				case 'is_archive':
					$condition_result = is_archive();
				break;
			}
		}

		return $condition_result;
	}

	// CALLBACKS
	// =========

	// UTILITIES
	// =========

}


// Initiate the singleton
WVWJPTC::init();


