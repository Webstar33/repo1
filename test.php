<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

asdasd
function dc(debug, dump, dieD) {
  echo "<pre>";
  if(!dump) {
    print_r(debug);
  } else {
    var_dump(debug);
  }
  echo "<pre>";
  if(dieD) {
    die();
  }
}

dc("test",0,1);

?>