<?php
# PHP Livereload. This package will enable livereload for development when files are updated on the server.
# GitHub: https://github.com/nathangrove/php-livereload.git

# Usage: Include script tag in your HTML
# <script src="livereload.php?delay=2000"></script>
# the delay param is optional defaults to (2000) milliseconds before next check.


# what paths do we monitor file changes?
$paths = ['.'];


# is this call the javascript performing a check?
if ($_GET['check']){
  $time = 0; $dh = opendir($path);
  foreach ($paths as $path){
    $files = getDirContents($path);
    foreach($files as $file){
      $t = filemtime($file);
      if ($t > $time) $time = $t;
    }
  }
  print $time;

} else {

  # pass in a "delay" parameter when fetching the javascript to check...
  $delay = isset($_GET['delay']) ? $_GET['delay'] : 2000;

  # create the javascript to spit out
  $javascript = "var t = ".time().";function r(){ if (this.responseText > t) window.location.reload(); };function c(){ var g = new XMLHttpRequest();g.addEventListener('load', r);g.open('GET', '/livereload.php?check=1');g.send();};window.setInterval(c,$delay);";

  header("Content-type: application/javascript");
  print $javascript;

}

# helper funciton to get directory contents
function getDirContents($dir, &$results = array()){
  $files = scandir($dir);
  foreach($files as $key => $value){
    $path = realpath($dir.DIRECTORY_SEPARATOR.$value);
    if(!is_dir($path)) {
      $results[] = $path;
    } else if($value != "." && $value != "..") {
      getDirContents($path, $results);
      $results[] = $path;
    }
  }
  return $results;
}

?>
