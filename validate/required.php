<?php namespace lib\validate {
  
  class required {
    
    public static function test( $input ) {
      return !empty( $input );
    }
    
  }
  
} ?>