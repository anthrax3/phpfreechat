<?php

class Container_channels {
  
  static public function getChannelsDir() {
    $datadir = __DIR__.'/../data';
    $cdir = $datadir.'/channels';
    return $cdir;
  }
  
  static public function generateCid() {
    $cdir = self::getChannelsDir();
    do {
      $cid = sha1(uniqid('', true));
      $cpath = $cdir.'/'.$cid.'/';
    } while (file_exists($cpath));
    @mkdir($cpath, 0777, true);
    @mkdir($cpath.'/users', 0777, true);
    return $cid;
  }
  
  
  static public function getChannelMsgPath($cid, $mid) {
    $cdir = self::getChannelsDir();
    $mpath = $cdir.'/'.$cid.'/msg/'.$mid.'.json';
    return $mpath;
  }
  
  static public function getChannelUserPath($cid, $uid) {
    $cdir = self::getChannelsDir();
    $cupath = $cdir.'/'.$cid.'/users/'.$uid;
    return $cupath;
  }
  
  static public function getChannelUsers($cid) {
    $cdir = self::getChannelsDir();
    $cupath = $cdir.'/'.$cid.'/users/';  
    $subscribers = array();
    foreach(scandir($cupath) as $value) {
      if($value === '.' || $value === '..') {continue;}
      $subscribers[] = $value;
    }
    return $subscribers;
  }
  
  static public function checkChannelUser($cid, $uid) {
    $cdir = self::getChannelsDir();
    $cupath = $cdir.'/'.$cid.'/users/'.$uid;  
    return file_exists($cupath);
  }  
}
