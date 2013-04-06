<?php

// Simple Icecast XML config editor Interface
// April 2013
// by ->(xesco)<-

Interface IcecastConfigInterface {

// add a new mountpoint
public function addMount($mount, $password, $username, $config);

// delete mountpoint
public function delMount($mount);

// set a new password for a mountpoint
public function changeMountPass($mount, $password);

// change mountpoint
public function changeMountPoint($old_mount, $new_mount);

// set admin user and password for web acess
public function setAdminWeb($user, $password);

}
