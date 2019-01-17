<?php

$wp_user = wp_get_current_user();
$php_user = get_current_user();
$other_user = get_random_current_user();
$namespaced_user = MyFramework\get_current_user();
echo $wp_user;
echo $php_user;
echo $other_user;
echo $namespaced_user; // ignore this get_current_user()
echo 'get_current_user';
echo "get_current_user()";
