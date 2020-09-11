<?php
/**
 * Privacy Subsystem implementation for auth_leeloolxp_tracking_sso.
 *
 * @package    auth_leeloolxp_tracking_sso
 * @author Leeloo LXP <info@leeloolxp.com>
 * @copyright  2020 Leeloo LXP (https://leeloolxp.com)
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
namespace auth_leeloolxp_tracking_sso\privacy;

use core_privacy\local\metadata\collection;

defined('MOODLE_INTERNAL') || die();

/**
 * Provider implementation for auth_leeloolxp_tracking_sso.
 *
 */
class provider implements
    \core_privacy\local\metadata\provider
{

    /**
     * Returns meta data about this system.
     *
     * @param   collection $collection The initialised collection to add items to.
     * @return  collection A listing of user data stored through this system.
     */
    public static function get_metadata(collection $collection) : collection {
        // Data collected by the client.
        $collection->add_external_location_link(
            'leeloolxp_tracking_sso_client',
            [
                'fullname' => 'privacy:metadata:fullname',
                'email' => 'privacy:metadata:email'
            ],
            'privacy:metadata'
        );

        return $collection;
    }

}