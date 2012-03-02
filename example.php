<?php namespace {
  
  require 'validate.php';
  require 'validate/alpha.php';
  require 'validate/alphanumeric.php';
  require 'validate/between.php';
  require 'validate/email.php';
  require 'validate/numeric.php';
  require 'validate/ISBN.php';
  require 'validate/ISSN.php';
  require 'validate/URL.php';
  require 'validate/required.php';
  
  print '<pre>';
  
  $start = microtime( TRUE );
  
  $test = lib\validate::test(
    [
      'name'  => 'John',
      'nick'  => 'Dude',
      'email' => 'john.doe@gmail.com'
    ],
    [
      'name'  => 'required | between(2,20) | alpha, message: please specify a name',
      'nick'  => 'between(1,20) | alphanumeric',
      'email' => 'required | email, message: invalid email address'
    ]
  );
  
  $time = microtime( TRUE );
  $time = round( $time - $start, 6 );
  
  var_dump( $test );
  
  print "\nvalidated in {$time} seconds";
  
} ?>