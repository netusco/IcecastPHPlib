<?php

require_once 'IcecastUserManagement.php';



//$icecast = new IcecastUserManagement();
$icecast = new IcecastUserManagement('./icecast.xml');
$icecast->setAdminWeb('itworks','itworks');
$icecast->changeMountPoint('/test.ogg','/itworks.ogg');
$icecast->changeMountPass('/sdj_stream_133.ogg','new_password');
$icecast->delMount('/txepe.ogg');
$icecast->addMount('/prova', 'prova');

?>
