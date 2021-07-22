<?php
/**
 * This file dumps all the Dochooks to an external file: /src/inc/hooks.php
 * It's used only when OP Cache has `save_comments` setting saved to false.
 *
 * @usage: wp eval-file dump-hooks.php
 * @usage: wp eval-file wp-content/plugins/{plugin-name}/vendor/bin/dump-hooks.php
 *
 * @package micropackage/dochooks
 */

use Micropackage\DocHooks\Hooks;

if ( ! isset( $args[0] ) ) {
	WP_CLI::error( 'Output file not specified.' );
	return;
}

$hooks_file = $args[0];

$hooks   = Hooks::get();
$objects = $hooks->get_hooked_objects();

$hook_functions = [];

// Loop over each class who added own hooks.
foreach ( $objects as $class_name => $data ) {
	$count = 0;

	$callback_object_name = '$this->objects[\'' . $class_name . '\'][\'instance\']';

	foreach ( $data['hooks'] as $hook ) {
		$hook_functions[] = sprintf(
			"add_%s( '%s', [ %s, '%s' ], %d, %d );",
			$hook['type'],
			$hook['name'],
			$callback_object_name,
			$hook['callback'],
			$hook['priority'],
			$hook['arg_count']
		);

		$count++;
	}

	WP_CLI::log( "{$class_name} added {$count} hooks" );
}

// // Clear the hooks file.
// $hooks_file = EW_DIR_PATH . '/src/inc/hooks.php';

if ( file_exists( $hooks_file ) ) {
	unlink( $hooks_file );
}

$hook_functions = implode( "\n", $hook_functions );

// Save the content.
$file_content = <<<EOT
<?php
/**
 * Hooks compatibilty file.
 *
 * Automatically generated with bin/dump-hooks.php file.
 */

// phpcs:disable

{$hook_functions}

EOT;

// phpcs:ignore WordPress.WP.AlternativeFunctions.file_system_read_file_put_contents
file_put_contents( $hooks_file, $file_content );
WP_CLI::success( 'All the hooks dumped!' );
