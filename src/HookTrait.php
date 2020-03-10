<?php
/**
 * Activates the docblock hooks for the class.
 *
 * Use one of the following in method docblock to
 * register an action, filter or shortcode:
 *
 * @action hook_name priority
 * @filter filter_name priority
 * @shortcode shortcode_name
 *
 * @package micropackage/dochooks
 */

namespace Micropackage\DocHooks;

/**
 * HookTrait class
 */
trait HookTrait {

	/**
	 * Pattern for doc hooks.
	 *
	 * @since 1.0.0
	 * @var   string
	 */
	protected static $pattern = '#\* @(?P<type>filter|action|shortcode)\s+(?P<name>[a-z0-9\-\.\/_]+)(\s+(?P<priority>\d+))?#';

	/**
	 * Array with added hooks.
	 *
	 * @since 1.0.0
	 * @var   array
	 */
	protected $_called_doc_hooks = []; // phpcs:ignore

	/**
	 * Add actions/filters from the method of a class based on DocBlock
	 *
	 * @since  1.0.0
	 * @param  mixed $object The class object or null.
	 * @return mixed         The subject.
	 */
	public function add_hooks( $object = null ) {

		if ( is_null( $object ) ) {
			$object = $this;
		}

		$class_name = get_class( $object );

		// Don't add the hooks twice.
		if ( isset( $this->_called_doc_hooks[ $class_name ] ) ) {
			return $object;
		}

		$reflector = new \ReflectionObject( $object );

		$this->_called_doc_hooks[ $class_name ] = [];

		foreach ( $reflector->getMethods() as $method ) {
			$doc       = $method->getDocComment();
			$arg_count = $method->getNumberOfParameters();

			if ( preg_match_all( self::$pattern, $doc, $matches, PREG_SET_ORDER ) ) {

				foreach ( $matches as $match ) {

					$type = $match['type'];
					$name = $match['name'];

					$priority = empty( $match['priority'] ) ? 10 : intval( $match['priority'] );
					$callback = [ $object, $method->getName() ];

					call_user_func( [ $this, "add_{$type}" ], $name, $callback, compact( 'priority', 'arg_count' ) );

					$this->_called_doc_hooks[ $class_name ][] = [
						'name'      => $name,
						'type'      => $type,
						'callback'  => $method->getName(),
						'priority'  => $priority,
						'arg_count' => $arg_count,
					];

				}
			}
		}

		return $object;

	}

	/**
	 * Hooks a function on to a specific filter
	 *
	 * @since  1.0.0
	 * @param  atring $name     The hook name.
	 * @param  array  $callback The class object and method.
	 * @param  array  $args     An array with priority and arg_count.
	 * @return mixed
	 */
	public function add_filter( $name, $callback, $args = [] ) {
		return $this->_add_hook( 'filter', $name, $callback, $this->_args( $args ) );
	}

	/**
	 * Hooks a function on to a specific action
	 *
	 * @since  1.0.0
	 * @param  string $name     The hook name.
	 * @param  array  $callback The class object and method.
	 * @param  array  $args     An array with priority and arg_count.
	 * @return mixed
	 */
	public function add_action( $name, $callback, $args = [] ) {
		return $this->_add_hook( 'action', $name, $callback, $this->_args( $args ) );
	}

	/**
	 * Hooks a function on to a specific shortcode
	 *
	 * @since  1.0.0
	 * @param  string $name     The shortcode name.
	 * @param  array  $callback The class object and method.
	 * @return mixed
	 */
	public function add_shortcode( $name, $callback ) {
		return $this->_add_hook( 'shortcode', $name, $callback );
	}

	/**
	 * Gets hook calls
	 *
	 * @since  1.0.2
	 * @return array
	 */
	public function get_calls() {
		return $this->_called_doc_hooks;
	}

	/**
	 * Hooks a function on to a specific action/filter
	 *
	 * @since  1.0.0
	 * @param  string $type     The hook type. Options are action/filter.
	 * @param  string $name     The hook name.
	 * @param  array  $callback Callback.
	 * @param  array  $args     An array with priority and arg_count.
	 * @return mixed
	 */
	protected function _add_hook( $type, $name, $callback, $args = [] ) {

		$priority  = isset( $args['priority'] ) ? $args['priority'] : 10;
		$arg_count = isset( $args['arg_count'] ) ? $args['arg_count'] : PHP_INT_MAX;

		$function = sprintf( 'add_%s', $type );

		return call_user_func( $function, $name, $callback, $priority, $arg_count );

	}

	/**
	 * Merges the hook args with defaults
	 *
	 * @since  1.0.0
	 * @param  array $args Arguments.
	 * @return array
	 */
	protected function _args( $args ) {

		return array_merge( [
			'priority'  => 10,
			'arg_count' => PHP_INT_MAX,
		], $args );

	}

}
