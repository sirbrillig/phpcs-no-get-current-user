<?php

use MyFramework\{ get_current_user };

$wp_user = wp_get_current_user();
$php_user = get_current_user();
$other_user = get_random_current_user();
$namespaced_user = MyFramework\get_current_user();
echo $wp_user;
echo $php_user;
echo $other_user;
echo $namespaced_user;
$get_current_user = function () {
	return 'hello';
};
echo $get_current_user();
