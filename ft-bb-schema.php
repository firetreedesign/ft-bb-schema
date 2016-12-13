<?php
/**
 * Plugin Name: Schema.org Settings for Beaver Builder
 * Plugin URI: http://firetreedesign.com/
 * Description: Allows you to identify structured data in Beaver Builder rows.
 * Version: 1.0.0
 * Author: FireTree Design, LLC <support@firetreedesign.com>
 * Author URI: https://firetreedesign.com/
 * Text Domain: ft-bb-schema
 * License:     GPL-2.0+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 *
 * @package FTBBSchema
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * FireTree Beaver Builder Schema.org Settings
 */
final class FTBBSchema {

	/**
	 * Initialize the class
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	static public function init() {

		// Register Row Settings.
		add_filter( 'fl_builder_register_settings_form', __CLASS__ . '::row_settings', 10, 2 );

		// Row HTML Output.
		add_action( 'fl_builder_before_render_row',	__CLASS__ . '::row_html_top', 10, 2 );
		add_action( 'fl_builder_after_render_row',	__CLASS__ . '::row_html_bottom', 10, 2 );

	}

	/**
	 * The row settings
	 *
	 * @since 1.0.0
	 *
	 * @param  array  $form The form.
	 * @param  string $id   The ID.
	 *
	 * @return [type]       [description]
	 */
	static public function row_settings( $form, $id ) {

		$row_settings = array(
			'title'         => __( 'Schema.org', 'ft-bb-schema' ),
			'sections'      => array(
				'general'       => array(
					'title'         => __( 'Web Page Element', 'ft-bb-schema' ),
					'fields'        => array(
						'ft_bb_schema_thing' => array(
							'type'          => 'select',
							'label'         => __( 'Type', 'ft-bb-schema' ),
							'default'       => 'none',
							'options'       => array(
								'none'		=> __( 'None', 'ft-bb-schema' ),
								'WPHeader'	=> __( 'Web Page Header (WPHeader)', 'ft-bb-schema' ),
								'WPFooter'	=> __( 'Web Page Footer (WPFooter)', 'ft-bb-schema' ),
							),
							'preview'      => array(
						        'type'	=> 'none',
						    ),
						),
					),
				),
			),
		);

		if ( 'row' === $id ) {
			$form['tabs'] = array_merge(
				array_slice( $form['tabs'], 0, 1 ),
				array( 'ft_bb_schema' => $row_settings ),
				array_slice( $form['tabs'], 1 )
			);
		}
		return $form;

	}

	/**
	 * Row HTML - Top
	 *
	 * @since 1.0.0
	 *
	 * @param  object $row    The row object.
	 * @param  object $groups The groups object.
	 *
	 * @return void
	 */
	static public function row_html_top( $row, $groups ) {

		if ( FLBuilderModel::is_builder_active() ) {
			return;
		}

		$thing = $row->settings->ft_bb_schema_thing;

		if ( 'none' !== $thing && strlen( $thing ) > 0 ) {
			echo '<' . esc_html( FTBBSchema::get_element( $thing ) ) . ' class="ft-bb-schema" itemscope itemtype="http://schema.org/' .  esc_attr( $thing ) . '">';
		}

	}

	/**
	 * Row HTML - Bottom
	 *
	 * @since 1.0.0
	 *
	 * @param  object $row    The row object.
	 * @param  object $groups The groups object.
	 *
	 * @return void
	 */
	static public function row_html_bottom( $row, $groups ) {

		if ( FLBuilderModel::is_builder_active() ) {
			return;
		}

		$thing = $row->settings->ft_bb_schema_thing;

		if ( 'none' !== $thing && strlen( $thing ) > 0 ) {
			echo '</' . esc_html( FTBBSchema::get_element( $thing ) ) . '>';
		}

	}

	/**
	 * Get the element
	 *
	 * @since 1.0.0
	 *
	 * @param  string $thing The thing.
	 *
	 * @return string
	 */
	static private function get_element( $thing ) {

		$element = 'div';

		switch ( $thing ) {
			case 'WPHeader':
				$element = 'header';
				break;
			case 'WPFooter':
				$element = 'footer';
				break;
		}

		return $element;

	}

}

FTBBSchema::init();
