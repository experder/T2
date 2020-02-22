<?php
/*GPL
 * This file is part of the T2 toolbox;
 * Copyright (C) 2014-2020 Fabian Perder (t2@qnote.de) and contributors
 * T2 comes with ABSOLUTELY NO WARRANTY. This is free software, and you are welcome to redistribute it under
 * certain conditions. See the GNU General Public License (file 'LICENSE' in the root directory) for more details.
 GPL*/

namespace t2\core\service;

use t2\core\Database;
use t2\core\Error;
use t2\core\form\Form;
use t2\core\form\Formfield_password;
use t2\core\form\Formfield_text;
use t2\core\Message;
use t2\core\Page;
use t2\core\Warning;

class Login {//TODO(F):Logout

	private static $session_cookie_name = 'T2_session';
	private static $user_fields = 'id,ref_id,username,display_name';

	public static function get_user() {

		$user = self::check_session();
		if ($user !== false) {
			return $user;
		}

		$user = self::prompt_credentials();
		$uid = $user['id'];

		$ok = self::new_session($uid);
		if (!$ok) {
			new Error("SESSION_INITIALIZATION_ERROR", "Couldn't initialize session.");
		}

		Page::$compiler_messages[] = new Message(Message::TYPE_CONFIRM, "Login successful. Welcome!");
		return $user;
	}

	private static function check_session() {
		if (isset($_COOKIE[self::$session_cookie_name]) && $_COOKIE[self::$session_cookie_name] !== '-') {
			$session_id = $_COOKIE[self::$session_cookie_name];

			$core_sessions = DB_CORE_PREFIX . '_sessions';
			$core_user = DB_CORE_PREFIX . '_user';
			$session_data = Database::select_single_(
				"SELECT expires,`user` as " . self::$user_fields . " FROM $core_sessions LEFT JOIN $core_user on `user`=$core_user.`id` WHERE session_id=:session_id"
				, array(
					"session_id" => $session_id,
				)
			);
			if (!$session_data) {
				Page::$compiler_messages[] = new Message(Message::TYPE_INFO, "Session not found.");
			} else {
				$expires = $session_data["expires"];
				if ($expires < time()) {
					Page::$compiler_messages[] = new Message(Message::TYPE_INFO, "Your session has expired.");
					Database::get_singleton()->update("DELETE FROM `$core_sessions` WHERE session_id=:session_id LIMIT 1;", array(
						":session_id" => $session_id,
					));
				} else {
					self::update_session($session_id, $expires);
					unset($session_data['expires']);
					return $session_data;
				}
			}
			self::set_cookie('-');
		}
		return false;
	}

	private static function update_session($session_id, $session_expires) {
		$expires = self::session_expires();
		if ($expires == $session_expires) {
			return;
		}
		$core_sessions = DB_CORE_PREFIX . '_sessions';
		$rowcount = Database::get_singleton()->update("UPDATE $core_sessions SET expires=:expires WHERE session_id=:session_id", array(
			":expires" => $expires,
			":session_id" => $session_id,
		));
		if ($rowcount !== 1) {
			new Warning("Couldn't update session!");
		}
	}

	private static function session_expires() {
		/**
		 * @var int $session_age
		 */
		$session_age = Config::get_value_core('SESSION_EXPIRES');
		$expires = time() + $session_age;
		return $expires;
	}

	public static function new_session($uid) {

		$session_id = bin2hex(Php7::random_bytes(10));
		$expires = self::session_expires();
		$core_sessions = DB_CORE_PREFIX . '_sessions';
		$ok = Database::insert_assoc_($core_sessions, array(
			"user" => $uid,
			"session_id" => $session_id,
			"expires" => $expires,
		));
		if ($ok) {
			$ok2 = self::set_cookie($session_id);
			if ($ok2) {
				return true;
			} else {
				new Error("Internal_error", "Internal error.");
			}
		}
		return false;
	}

	public static function set_cookie($session_id) {
		return setcookie(self::$session_cookie_name, $session_id, 0, '/');
	}

	public static function prompt_credentials() {

		$page = new Page("PAGEID_CORE_LOGIN", "Login");
		$val_from_request = true;

		if (Request::cmd('t2_dologin')) {
			$user = self::check_credentials(Request::value('username'), Request::value('password'));
			if ($user === false) {
				$page->add_message_error("Wrong credentials!");
				$val_from_request = false;
			} else {
				return $user;
			}
		}

		$LOGIN_HTML = Config::get_value_core('LOGIN_HTML');

		$page->add($LOGIN_HTML);
		$page->add($form = new Form("t2_dologin", "", "Login"));
		$form->add_field(new Formfield_text("username", "Username", null, $val_from_request, array("id" => "id_t2_login_prompt_username")));
		$page->set_focusFieldId('id_t2_login_prompt_username');
		$form->add_field(new Formfield_password("password", "Password", null, $val_from_request));

		$page->send_and_quit();
		return false;
	}

	public static function check_credentials($username, $password) {
		$password_hash = md5($password);

		$core_user = DB_CORE_PREFIX . '_user';
		$data = Database::select_single_("SELECT " . self::$user_fields . " FROM $core_user WHERE username=:username AND pass_hash=:password_hash;", array(
			":username" => $username,
			":password_hash" => $password_hash,
		));

		if (!$data) {
			return false;
		}

		return $data;
	}

}
