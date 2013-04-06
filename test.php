<?php

require_once 'Icecast_User_Management.php';

$icecast = new Icecast_User_Management('./icecast.xml');
$icecast->setAdminWeb('itworks','itworks');
$icecast->changeMountPoint('/test.ogg','/itworks.ogg');
$icecast->changeMountPass('/sdj_stream_133.ogg','new_password');

?>
