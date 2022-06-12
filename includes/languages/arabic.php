<?php
  function lang($phrase) {
    static $lang = array(
         'MESSAGE'=> 'welcome in arabic'
 );
    return $lang[$phrase];
}