<?php

add_filter( 'login_errors', 'prevent_login_error' );

function prevent_login_error(){
  return '';
}
