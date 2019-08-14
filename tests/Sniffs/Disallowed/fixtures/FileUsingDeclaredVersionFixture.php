<?php

namespace Declared;

function get_current_user() {
	return 'hello';
}

$wp_user = wp_get_current_user();
$php_user = get_current_user();
$other_user = get_random_current_user();
$namespaced_user = MyFramework\get_current_user();
echo $wp_user;
echo $php_user;
echo $other_user;
echo $namespaced_user;
