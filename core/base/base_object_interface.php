<?php

namespace Elementor\Core\Base;

/**
 * Base Object
 *
 * Base class that provides basic settings handling functionality.
 *
 * @since 2.3.0
 */
interface BaseObjectInterface {

	/**
	 * Get Settings.
	 *
	 * @param string|null $setting Optional. The key of the requested setting. Default is null.
	 *
	 * @return mixed An array of all settings, or a single value if `$setting` was specified.
	 * @since 2.3.0
	 * @access public
	 *
	 */
	public function get_settings( string $setting = null);

	/**
	 * Set settings.
	 *
	 * @param array|string $key If key is an array, the settings are overwritten by that array. Otherwise, the
	 *                            settings of the key will be set to the given `$value` param.
	 *
	 * @param mixed $value Optional. Default is null.
	 * @since 2.3.0
	 * @access public
	 *
	 */
	public function set_settings( $key, $value = null);

	/**
	 * Delete setting.
	 *
	 * Deletes the settings array or a specific key of the settings array if `$key` is specified.
	 * @param string|null $key Optional. Default is null.
	 * @since 2.3.0
	 * @access public
	 *
	 */
	public function delete_setting( string $key = null);

	public function merge_properties( array $default_props, array $custom_props, array $allowed_props_keys = []);

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
	 * @since 3.1.0
	 *
	 */
	public function has_own_method( string $method_name, string $base_class_name = null): bool;
}
