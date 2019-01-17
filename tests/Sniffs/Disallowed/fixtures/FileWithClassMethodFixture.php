<?php

class MyClass extends Get_Current_User {
	public function do_something_with_get_current_user() {
		echo $this->get_current_user();
		echo self::get_current_user();
	}

	public function get_current_user() {
		return 'human';
	}
}
