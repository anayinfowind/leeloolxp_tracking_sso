<?php
// This file is part of Moodle - http://moodle.org/
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
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * Admin settings and defaults
 *
 * @package auth_leeloolxp_tracking_sso
 * @copyright  2020 Leeloo LXP (https://leeloolxp.com)
 * @author Leeloo LXP <info@leeloolxp.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
defined('MOODLE_INTERNAL') || die;

if ($ADMIN->fulltree) {

    $settings->add(new admin_setting_configtext(
        'auth_leeloolxp_tracking_sso/license',
        get_string('license', 'auth_leeloolxp_tracking_sso'),
        get_string('license', 'auth_leeloolxp_tracking_sso'),
        0
    ));

    $settings->add(new admin_setting_configcheckbox(
        'auth_leeloolxp_tracking_sso/sso_required_admin_approval_student',
        get_string('sso_required_admin_approval_student_label', 'auth_leeloolxp_tracking_sso'),
        get_string('sso_required_admin_approval_student_desc', 'auth_leeloolxp_tracking_sso'),
        1
    ));
}
