<?php
/**
 * Hooks storage class
 *
 * @package micropackage/dochooks
 */

namespace Micropackage\DocHooks;

use Micropackage\Singleton\Singleton;

/**
 * Hooks class
 *
 * @since 1.1
 */
class Hooks extends Singleton {
	/**
	 * Hooked objects
	 *
	 * @var  array
	 */
	private $objects = [];

	/**
	 * Adds object.
	 *
	 * @param  object $object Object.
	 * @return void
	 */
	public function add_object( $object ) {
		$class_name = get_class( $object );

		if ( ! isset( $this->objects[ $class_name ] ) ) {
			$this->objects[ $class_name ] = [
				'instance' => $object,
				'hooks'    => [],
			];
		}
	}

	/**
	 * Checks if given object already has been added.
	 *
	 * @param  object $object Object to check.
	 * @return boolean        `True` if object has been added, `false` otherwise.
	 */
	public function has_object( $object ) {
		$class_name = get_class( $object );

		return isset( $this->objects[ $class_name ] );
	}

	/**
	 * Adds hook.
	 *
	 * @param  object $object Object.
	 * @param  array  $data   Hook data.
	 * @return void
	 */
	public function add_hook( $object, $data ) {
		$class_name = get_class( $object );

		if ( ! isset( $this->objects[ $class_name ] ) ) {
			$this->add_object( $object );
		}

		$this->objects[ $class_name ]['hooks'][] = $data;
	}

	/**
	 * Gets hooked objects.
	 *
	 * @return array
	 */
	public function get_hooked_objects() {
		return $this->objects;
	}

	/**
	 * Returns hooks registered for given object.
	 *
	 * @since  1.1.0
	 * @param  object $object Object to get the hooks for.
	 * @return array          Hooks registered for given object.
	 */
	public function get_hooks_for_object( $object ) {
		$class_name = get_class( $object );

		return isset( $this->objects[ $class_name ] ) ? $this->objects[ $class_name ] : [];
	}

	/**
	 * Loads dumped hooks..
	 *
	 * @param  string $file File with hooks.
	 * @return void
	 */
	public function load_hooks( $file ) {
		if ( file_exists( $file ) ) {
			include_once $file;
		}
	}
}
