<?php

namespace Elementor\Core\Base;

require_once 'base_object_interface.php';

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Base Object
 *
 * Base class that provides basic settings handling functionality.
 *
 * @since 2.3.0
 */
class Base_Object implements BaseObjectInterface {


	/**
	 * Settings.
	 *
	 * Holds the object settings.
	 *
	 * @access private
	 *
	 * @var array
	 */
	private $settings;

	/**
	 * Get Settings.
	 *
	 * @param string|null $setting Optional. The key of the requested setting. Default is null.
	 *
	 * @return mixed An array of all settings, or a single value if `$setting` was specified.
	 *@since 2.3.0
	 * @access public
	 *
	 */
	final public function get_settings( string $setting = null ) {
		$this->ensure_settings();

		return self::get_items( $this->settings, $setting );
	}

	/**
	 * Set settings.
	 *
	 * @since 2.3.0
	 * @access public
	 *
	 * @param array|string $key   If key is an array, the settings are overwritten by that array. Otherwise, the
	 *                            settings of the key will be set to the given `$value` param.
	 *
	 * @param mixed        $value Optional. Default is null.
	 */
	final public function set_settings( $key, $value = null ) {
		$this->ensure_settings();

		if ( is_array( $key ) ) {
			$this->settings = $key;
		} else {
			$this->settings[ $key ] = $value;
		}
	}

	/**
	 * Delete setting.
	 *
	 * Deletes the settings array or a specific key of the settings array if `$key` is specified.
	 * @param string|null $key Optional. Default is null.
	 * @since 2.3.0
	 * @access public
	 *
	 */
	public function delete_setting( string $key = null ) {
		if ( $key ) {
			unset( $this->settings[ $key ] );
		} else {
			$this->settings = [];
		}
	}

	final public function merge_properties( array $default_props, array $custom_props, array $allowed_props_keys = [] ): array {
		$props = array_replace_recursive( $default_props, $custom_props );

		if ( $allowed_props_keys ) {
			$props = array_intersect_key( $props, array_flip( $allowed_props_keys ) );
		}

		return $props;
	}

	/**
	 * Get items.
	 *
	 * Utility method that receives an array with a needle and returns all the
	 * items that match the needle. If needle is not defined the entire haystack
	 * will be returned.
	 *
	 * @param array  $haystack An array of items.
	 * @param string|null $needle   Optional. Needle. Default is null.
	 *
	 * @return mixed The whole haystack or the needle from the haystack when requested.
	 * @since 2.3.0
	 * @access protected
	 * @static
	 *
	 */
	final protected static function get_items( array $haystack, string $needle = null ) {
		if ( $needle ) {
			return isset( $haystack[ $needle ] ) ? $haystack[ $needle ] : null;
		}

		return $haystack;
	}

	/**
	 * Get init settings.
	 *
	 * Used to define the default/initial settings of the object. Inheriting classes may implement this method to define
	 * their own default/initial settings.
	 *
	 * @since 2.3.0
	 * @access protected
	 *
	 * @return array
	 */
	protected function get_init_settings() {
		return [];
	}

	/**
	 * Ensure settings.
	 *
	 * Ensures that the `$settings` member is initialized
	 *
	 * @since 2.3.0
	 * @access private
	 */
	private function ensure_settings() {
		if ( null === $this->settings ) {
			$this->settings = $this->get_init_settings();
		}
	}

	/**
	 * Has Own Method
	 *
	 * Used for check whether the method passed as a parameter was declared in the current instance or inherited.
	 * If a base_class_name is passed, it checks whether the method was declared in that class. If the method's
	 * declaring class is the class passed as $base_class_name, it returns false. Otherwise (method was NOT declared
	 * in $base_class_name), it returns true.
	 *
	 * Example #1 - only $method_name is passed:
	 * The initial declaration of `register_controls()` happens in the `Controls_Stack` class. However, all
	 * widgets which have their own controls declare this function as well, overriding the original
	 * declaration. If `has_own_method()` would be called by a Widget's class which implements `register_controls()`,
	 * with 'register_controls' passed as the first parameter - `has_own_method()` will return true. If the Widget
	 * does not declare `register_controls()`, `has_own_method()` will return false.
	 *
	 * Example #2 - both $method_name and $base_class_name are passed
	 * In this example, the widget class inherits from a base class `Widget_Base`, and the base implements
	 * `register_controls()` to add certain controls to all widgets inheriting from it. `has_own_method()` is called by
	 * the widget, with the string 'register_controls' passed as the first parameter, and 'Elementor\Widget_Base' (its full name
	 * including the namespace) passed as the second parameter. If the widget class implements `register_controls()`,
	 * `has_own_method` will return true. If the widget class DOESN'T implement `register_controls()`, it will return
	 * false (because `Widget_Base` is the declaring class for `register_controls()`, and not the class that called
	 * `has_own_method()`).
	 *
	 * @param string $method_name
	 * @param string|null $base_class_name
	 *
	 * @return bool True if the method was declared by the current instance, False if it was inherited.
	 *@since 3.1.0
	 *
	 */
	public function has_own_method( string $method_name, string $base_class_name = null ) :bool {
		try {
			$reflection_method = new \ReflectionMethod( $this, $method_name );

			// If a ReflectionMethod is successfully created, get its declaring class.
			$declaring_class = $reflection_method->getDeclaringClass();
		} catch ( \Exception $e ) {
			return false;
		}

		if ( $base_class_name ) {
			return $base_class_name !== $declaring_class->name;
		}

		return get_called_class() === $declaring_class->name;
	}
}
