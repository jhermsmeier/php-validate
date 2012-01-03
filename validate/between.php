<?php namespace lib\validate {
  
  class between {
    
    public static function test( $input, $between ) {
      
      if( is_string($input) )
        $input = strlen($input);
      
      if( is_array($input) || is_object($input) )
        $input = count( (array) $input );
      
      if( is_numeric($input) )
        return ( $between[0] <= $input ) &&
          ( $between[1] >= $input );
      
      return FALSE;
      
    }
    
  }
  
} ?>