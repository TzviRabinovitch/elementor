<?php

namespace Elementor\Core\Experiments;

use  Elementor\Core\Base\BaseObjectInterface;

interface ExperimentsManagerInterface extends BaseObjectInterface {

	/**
	 * Add Feature
	 *
	 * @param array $options {
	 * @type string $name
	 * @type string $title
	 * @type string $tag
	 * @type string $description
	 * @type string $release_status
	 * @type string $default
	 * @type callable $on_state_change
	 * }
	 *
	 * @return array|null
	 * @throws \Exception
	 * @since 3.1.0
	 * @access public
	 *
	 */
	public function add_feature( array $options);

	/**
	 * Remove Feature
	 *
	 * @param string $feature_name
	 * @since 3.1.0
	 * @access public
	 *
	 */
	public function remove_feature( string $feature_name);

	/**
	 * Get Features
	 *
	 * @param string|null $feature_name Optional. Default is null
	 *
	 * @return array|null
	 * @since 3.1.0
	 * @access public
	 *
	 */
	public function get_features( string $feature_name = null);

	/**
	 * Get Active Features
	 *
	 * @return array
	 * @since 3.1.0
	 * @access public
	 *
	 */
	public function get_active_features(): array;

	/**
	 * Is Feature Active
	 *
	 * @param string $feature_name
	 *
	 * @return bool
	 * @since 3.1.0
	 * @access public
	 *
	 */
	public function is_feature_active( string $feature_name): bool;

	/**
	 * Set Feature Default State
	 *
	 * @param string $feature_name
	 * @param string $default_state
	 * @since 3.1.0
	 * @access public
	 *
	 */
	public function set_feature_default_state( string $feature_name, string $default_state);

	/**
	 * Get Feature Option Key
	 *
	 * @param string $feature_name
	 *
	 * @return string
	 * @since 3.1.0
	 * @access public
	 *
	 */
	public function get_feature_option_key( string $feature_name): string;

	/**
	 * Get Feature State Label
	 *
	 * @param array $feature
	 *
	 * @return string
	 */
	public function get_feature_state_label( array $feature): string;

	public function __construct();
}
