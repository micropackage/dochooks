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
 * HookAnnotations class
 */
class HookAnnotations {

	use HookTrait;

}
