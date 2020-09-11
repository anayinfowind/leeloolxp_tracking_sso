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
 * Main functions of the plugin.
 *
 * @package auth_leeloolxp_tracking_sso
 * @author Leeloo LXP <info@leeloolxp.com>
 * @copyright  2020 Leeloo LXP (https://leeloolxp.com)
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

require_once($CFG->libdir.'/authlib.php');

/**
 * Plugin to sync users to LeelooLXP account of the Moodle Admin
 */
class auth_plugin_leeloolxp_tracking_sso extends auth_plugin_base {
	
	/**
     * Constructor
     */
    function __construct() { 
		$this->authtype = 'leeloolxp_tracking_sso';
		$this->config = get_config('leeloolxp_tracking_sso');
	}

	/**
     * Check if user authenticated
     */
	function user_authenticated_hook(&$user, $username, $password){
		global $CFG;
		global $PAGE;
		global $DB;
		$plugin_url =  $CFG->wwwroot."/auth/leeloolxp_tracking_sso/";

		$username = $username;
		$password = $password;
		$user_email = $user->email;

		$user_fullname =  ucfirst($user->firstname)." ".ucfirst($user->lastname);

		$leeloolxp_license = $this->config->leeloolxp_license;

		$postData = '&license_key='.$leeloolxp_license;
		$ch = curl_init();  
 		$url = 'https://leeloolxp.com/api_moodle.php/?action=page_info';
	    curl_setopt($ch,CURLOPT_URL,$url);
	    curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
	    curl_setopt($ch,CURLOPT_HEADER, false); 
	    curl_setopt($ch, CURLOPT_POST, count($postData));
	    curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);  

	    $output = curl_exec($ch);
	 	curl_close($ch);
		$info_leeloolxp  = json_decode($output);
		
		if($info_leeloolxp->status!='false') {
	   	   $leeloolxp_url = $info_leeloolxp->data->install_url; 
	    } else {
	    	return true;
	    }

	    $lastlogin = date('Y-m-d h:i:s',$user->lastlogin);
        $fullname = ucfirst($user->firstname)." ".ucfirst($user->middlename)." ".ucfirst($user->lastname);
        $city =  $user->city;
        $country = $user->country;
        $timezone = $user->timezone;
        $skype = $user->skype;
        $idnumber = $user->idnumber;
        $institution = $user->institution;
        $department = $user->department;
        $phone = $user->phone1;
        $moodle_phone = $user->phone2;
        $adress = $user->adress;
        $firstaccess = $user->firstaccess;
        $lastaccess = $user->lastaccess;
        $lastlogin = $lastlogin;
        $lastip = $user->lastip;
        
        $description = $user->description;
        $description_of_pic = $user->imagealt;
        $alternatename = $user->alternatename;
        $web_page = $user->url;
        $img_url =  new moodle_url('/user/pix.php/'.$user->id.'/f1.jpg');
        
	    $user_exist_on_leeloolxp = $this->syncuser($username,$user_email,$password,$user_fullname,$leeloolxp_url,$fullname,$city,$country,$timezone,$skype,$idnumber,$institution,$department,$phone,$moodle_phone,$adress,$firstaccess,$lastaccess,$lastlogin,$lastip,$description,$description_of_pic, $alternatename,$web_page,$img_url);

		return true;
	}

	/**
     * Returns false if the user exists and the password is wrong.
     *
     * @param string $username is username
     * @param string $password is password
     * @return bool Authentication success or failure.
     */
    function user_login($username, $password) {
		return false;
	}

	/**
     * Sync user to LeelooLXP with his details.
     */
    function syncuser( $username,$email,$password,$user_fullname,$leeloolxp_url,$fullname,$city,$country,$timezone,$skype,$idnumber,$institution,$department,$phone,$moodle_phone,$adress,$firstaccess,$lastaccess,$lastlogin,$lastip,$description,$description_of_pic, $alternatename,$web_page,$img_url) {
		global $DB;
		
		$user_approval = $this->config->sso_required_admin_approval_student;

		$data = array(
			'username' => $username,
			'email' => $email,
			'password' => $password,
			'user_fullname' => $fullname,
			'user_approval' => $user_approval,
			'lastlogin' => $lastlogin,
			'city' => $city,
			'country' => $country,
			'timezone' => $timezone,
			'skype' => $skype,
			'idnumber' => $idnumber,
			'institution' => $institution,
			'department' => $department,
			'phone' => $phone,
			'moodle_phone' => $moodle_phone,
			'adress' => $adress,
			'firstaccess' => $firstaccess,
			'lastaccess' => $lastaccess,
			'lastlogin' => $lastlogin,
			'lastip' => $lastip,
			'user_description' => $description,
			'picture_description' => $description_of_pic,
			'alternate_name' => $alternate_name,
			'web_page' => $web_page,

		);
		
		$payload = json_encode($data);

		$url = $leeloolxp_url.'/admin/sync_moodle_course/sync_user_password_moodle';

		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, array("data"=>$payload,'img_url' => $img_url));
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 

		$output = curl_exec($ch);
		if(curl_getinfo($ch, CURLINFO_HTTP_CODE) !== 200) {
			var_dump($output);

		}
                                
		curl_close($ch);
		return $output;
	}

}
?>