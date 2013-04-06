<?php

// Simple Icecast XML config editor Implementation
// Check Interface for description
// April 2013
// by ->(xesco)<-

require_once('./Icecast_UM_Interface.php');

class IcecastUserManagement implements Icecast_UM_Interface {

    private $xml;
    private $file;

    function __construct($file) 
    {
        if(file_exists($file)) {
            $this->file = $file;
            $this->xml  = new DomDocument();
            $this->xml->formatOutput       = true;
            $this->xml->preserveWhiteSpace = false;
            $this->xml->load($file);
        }
        else 
            throw new Exception("File $file does not exist or bad xml!");
    }

    public function addMount($mount, $password)
    {
        //file_put_contents($this->file, $this->xml->saveXML());
    }
    
    public function delMount($mount)
    {
        //file_put_contents($this->file, $this->xml->saveXML());
    }
    
    public function changeMountPass($mount, $password)
    {
        foreach($this->xml->getElementsByTagName('mount-name') as $mp) 
        {
            if(strcmp($mp->nodeValue, $mount)==0) {
                $pass = $mp->parentNode->getElementsByTagName('password')->item(0);
                $pass->parentNode->replaceChild($this->xml->createElement('password', $password), $pass);
            }
        }
        file_put_contents($this->file, $this->xml->saveXML());
    }
    
    public function changeMountPoint($old_mount, $new_mount)
    {
        foreach($this->xml->getElementsByTagName('mount-name') as $mount) 
        {
            if(strcmp($mount->nodeValue, $old_mount)==0) {
               $mount->parentNode->replaceChild($this->xml->createElement('mount-name', $new_mount), $mount);
            }
        }
        file_put_contents($this->file, $this->xml->saveXML());
    }
    
    public function setAdminWeb($user, $password)
    {
        $admin_user = $this->xml->getElementsByTagName('admin-user')->item(0);
        $admin_user->parentNode->replaceChild($this->xml->createElement('admin-user', $user), $admin_user);
        $admin_pass = $this->xml->getElementsByTagName('admin-password')->item(0);
        $admin_pass->parentNode->replaceChild($this->xml->createElement('admin-password', $password), $admin_pass);
        file_put_contents($this->file, $this->xml->saveXML());
    }
}
?>
