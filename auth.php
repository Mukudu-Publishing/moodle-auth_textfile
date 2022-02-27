<?php
// This file is part of Moodle - https://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <https://www.gnu.org/licenses/>.

/**
 * Required Authentication class file.
 *
 * @package   auth_textfile
 * @copyright 2019 - 2021 Mukudu Ltd - Bham UK
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
defined('MOODLE_INTERNAL') || die();

require_once($CFG->libdir.'/authlib.php');

/**
 * Authentication class extending authetication base.
 *
 * @package   auth_textfile
 * @copyright 2019 - 2021 Mukudu Ltd - Bham UK
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class auth_plugin_textfile extends auth_plugin_base {

    /**
     * Class Constructor.
     */
    public function __construct() {
        $this->authtype = 'textfile';
    }

    /**
     * Returns true if this authentication plugin is "internal".
     *
     * Internal plugins use password hashes from Moodle user table for authentication.
     *
     * @return bool
     */
    public function is_internal() {
        return false;
    }

    /**
     * This is the primary method that is used by the authenticate_user_login()
     * function in moodlelib.php.
     *
     * This method should return a boolean indicating
     * whether or not the username and password authenticate successfully.
     *
     * Returns true if the username and password work and false if they are
     * wrong or don't exist.
     *
     * @param string $username The username (with system magic quotes)
     * @param string $password The password (with system magic quotes)
     *
     * @return bool Authentication success or failure.
     */
    public function user_login($username, $password) {
        global $CFG;
        // Our user text db.
        $userdb = $CFG->dataroot . '/userdb.tsv';
        if (file_exists($userdb)) {
            $users = file($userdb);   // Read file lines.
            foreach ($users as $userdetails) {
                // Split the line on tabs.
                list($user, $passwd) = explode("\t", trim($userdetails));
                if ($user == $username) {
                    if (md5($password) === $passwd) {
                        return true;
                    }
                }
            }
        }
        return false;
    }

    /**
     * Read user information from external database and returns it as array().
     * Function should return all information available. If you are saving
     * this information to moodle user-table you should honour synchronisation flags
     *
     * @param string $username username
     *
     * @return mixed array with no magic quotes or false on error
     */
    public function get_userinfo($username) {
        return array(
            'email' => $username
        );
    }

    /**
     * Returns false if this plugin is enabled but not configured.
     *
     * @return bool
     */
    public function is_configured() {
        return true;
    }

}
