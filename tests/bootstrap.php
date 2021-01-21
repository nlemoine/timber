<?php

$_tests_dir = getenv( 'WP_TESTS_DIR' );

if ( ! $_tests_dir ) {
	$_tests_dir = rtrim( sys_get_temp_dir(), '/\\' ) . '/wordpress-tests-lib';
}

if ( ! file_exists( $_tests_dir . '/includes/functions.php' ) ) {
	echo "Could not find $_tests_dir/includes/functions.php, have you run bin/install-wp-tests.sh ?" . PHP_EOL; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	exit( 1 );
}

// Give access to tests_add_filter() function.
require_once $_tests_dir . '/includes/functions.php';

function _manually_load_plugin() {
	global $timber;

	require dirname( __FILE__ ) . '/../vendor/autoload.php';
	$timber = new \Timber\Timber();

	require dirname( __FILE__ ) . '/../wp-content/plugins/advanced-custom-fields/acf.php';
	if ( file_exists( dirname( __FILE__ ) . '/../wp-content/plugins/co-authors-plus/co-authors-plus.php') ) {
		include dirname( __FILE__ ) . '/../wp-content/plugins/co-authors-plus/co-authors-plus.php';
	}
}

tests_add_filter( 'muplugins_loaded', '_manually_load_plugin' );

require $_tests_dir . '/includes/bootstrap.php';

require_once __DIR__.'/Timber_UnitTestCase.php';
// require_once __DIR__.'/TimberAttachment_UnitTestCase.php';
// require_once __DIR__.'/timber-mock-classes.php';

error_log('Use http://build.starter-theme.dev/ for testing with UI');

if ( !function_exists('is_post_type_viewable') ) {
	function is_post_type_viewable( $post_type_object ) {
 		return $post_type_object->publicly_queryable || ( $post_type_object->_builtin && $post_type_object->public );
 	}
}

// Make sure translations are installed.
Timber_UnitTestCase::install_translation( 'de_DE' );
