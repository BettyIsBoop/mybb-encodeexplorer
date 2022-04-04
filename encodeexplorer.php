<?php

/**
 * Changelog:
 *
 */

$plugins->add_hook('pre_output_page', 'encodeexplorer_pre_output_page');
$plugins->add_hook('post_output_page', 'encodeexplorer_post_output_page');
$plugins->add_hook('logout_end', 'encodeexplorer_logout_end');


function encodeexplorer_pre_output_page($contents) {
    global $mybb;
    session_start();
}
function encodeexplorer_post_output_page() {
    global $mybb;
    session_start();
    $user = $mybb->user;
    if (empty($user) || empty($user['uid'])) {
        unset($_SESSION['ee_user_name']);
        unset($_SESSION['ee_user_pass']);
        unset($_SESSION['ee_user_right']);
        unset($_SESSION['ee_user_dir']);
    }
    $user_permissions = user_permissions();
    $usergroup = $mybb->usergroup;
    if (empty($usergroup['issupermod'])) {
        unset($_SESSION['ee_user_name']);
        unset($_SESSION['ee_user_pass']);
        unset($_SESSION['ee_user_right']);
        unset($_SESSION['ee_user_dir']);
    }
    $usergroup = $mybb->usergroup;
    $isadmin = false;
    if (!empty($usergroup['gid']) && $usergroup['gid'] == 4) {
        $isadmin = true;
    } elseif (!empty($usergroup['all_usergroups']) && in_array(4,explode(',',$usergroup['all_usergroups']))) {
        $isadmin = true;
    }
    $issupermod = !empty($usergroup['issupermod']) && $usergroup['issupermod'];
    if($isadmin && !$issupermod) {
        $_SESSION['ee_user_name'] = 0;
        return;
    }
    $user = $mybb->user;
    $_SESSION['ee_user_name'] = $user['username'];
    $_SESSION['ee_user_pass'] = "1";
    $_SESSION['ee_user_right'] = "user";
    $_SESSION['ee_user_dir'] = "./users/uid{$user['uid']}";
    if($isadmin) {
        $_SESSION['ee_user_right'] = "admin";
    }
}
function encodeexplorer_logout_end($event) {
    unset($_SESSION['ee_user_name']);
    unset($_SESSION['ee_user_pass']);
    unset($_SESSION['ee_user_right']);
    unset($_SESSION['ee_user_dir']);
    unset($_SESSION);

}



function encodeexplorer_info() {
	return array(
		'name'          => 'encodeexplorer',
		'description'   => 'Encode explorer system hack.',
		'compatibility' => '18*',
		'author'        => 'betty',
		'authorsite'    => 'https://example.net/',
		'version'       => '1.0.0',
		'codename'      => 'encodeexplorer',
	);
}
