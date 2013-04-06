<?php

// Simple Icecast XML config editor Implementation
// Check Interface for description
// April 2013
// by ->(xesco)<-

require_once('./IcecastConfigInterface.php');
define("ICECAST_CONFIG_FILE","/etc/icecast2/icecast.xml");

class IcecastConfig implements IcecastConfigInterface {

    private $xml;
    private $file;

    function __construct($file=NULL) 
    {
        if(!isset($file)) $file = ICECAST_CONFIG_FILE;
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

    // last two parameters are optional
    public function addMount($mount, $password, $username=NULL, $config=NULL)
    {
        // check if mountpoint already exists
        foreach($this->xml->getElementsByTagName('mount-name') as $mp) 
        {
            if(strcmp($mp->nodeValue, $mount)==0)
                throw new Exception("Mountpoint $mount already exists!");
        }

        $mp = $this->xml->createElement('mount');
        $mp->appendChild($this->xml->createElement('mount-name', $mount));
        $mp->appendChild($this->xml->createElement('password', $password));
        if(isset($username))
            $mp->appendChild($this->xml->createElement('username', $username));

        // custom config
        if(isset($config)) { 
            foreach($config as $param => $value) {
                $mp->appendChild($this->xml->createElement($param, $value));
            }
        }
        // default config
        else {
            $mp->appendChild($this->xml->createElement('max-listeners', '5'));
            $mp->appendChild($this->xml->createElement('public', '0'));
            $mp->appendChild($this->xml->createElement('bitrate','160'));
            $mp->appendChild($this->xml->createElement('type', 'application/mp3'));
            $mp->appendChild($this->xml->createElement('subtype', 'mp3'));
            $mp->appendChild($this->xml->createElement('hidden', '1'));
            $mp->appendChild($this->xml->createElement('burst-on-connect', '0'));
        }
        $this->xml->getElementsByTagName('icecast')->item(0)->appendChild($mp);
        file_put_contents($this->file, $this->xml->saveXML());
    }
    
    public function delMount($mount)
    {
        foreach($this->xml->getElementsByTagName('mount-name') as $mp) 
        {
            if(strcmp($mp->nodeValue, $mount)==0) {
                $mp->parentNode->parentNode->removeChild($mp->parentNode);
            }
        }
        file_put_contents($this->file, $this->xml->saveXML());
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
