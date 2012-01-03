<?php namespace lib {
  
  /**
   * Validate
   * 
   * @copyright 2012 Jonas Hermsmeier
   * @author Jonas Hermsmeier <http://jhermsmeier.de>
   */
  class validate {
    
    /**
     * Runs a bucnh of tests against given $data
     * with set $rules.
     * 
     * Returns TRUE if the tests pass or an associative array of
     * occured errors and (if set) their messages otherwise.
     * 
     * @param array $data 
     * @param array $rules 
     * @return mixed
     */
    public static function test( $data, $rules ) {
      // test state
      $passed = TRUE;
      // holds occured errors
      $errors = array();
      // run test methods on given data
      foreach( $data as $field => &$value ) {
        // parse rules
        $ruleset = static::parse( $rules[$field] );
        // run test methods
        foreach( $ruleset['methods'] as $method => &$params ) {
          if( !static::$method( $value, $params ) ) {
            $passed = FALSE;
            if( isset( $ruleset['message'] ) )
              $errors[$field] = $ruleset['message'];
            else
              $errors[$field] = NULL;
            break;
          }
        }
      }
      return $passed ?: $errors;
    }
    
    /**
     * Parses rule definitions.
     * 
     * @param string $rule 
     * @return array
     */
    protected static function parse( $rule ) {
      // output
      $tests = array();
      // split up the rule definition
      $parts = preg_split( '{(\s+[|]\s+)|([,]\s+)}', $rule );
      // the rule parsing regex
      $pattern = '{^
        # test name
        ([a-z]+)(?:
          # parameters OR
          (?:[(](.*?)[)]) |
          # message
          (?:[:]\s+(.*?))
        )?
      $}xi';
      // parse each rule from the rule definition
      foreach( $parts as &$rule ) {
        if( preg_match( $pattern, $rule, $m ) ) {
          // set error message and continue to
          // avoid a 'message' test method being set
          if( isset( $m[3] ) ) {
            $tests['message'] = $m[3];
            continue;
          }
          // set test method
          $tests['methods'][$m[1]] = NULL;
          // set test method params, if given
          if( isset( $m[2] ) && !empty( $m[2] ) ) {
            $tests['methods'][$m[1]] = explode( ',', $m[2] );
          }
        }
      }
      return $tests;
    }
    
    /**
     * Used to call test methods. When using a
     * namespace based class autoloader this has the effect
     * that only needed test methods will be loaded.
     * 
     * @param string $method 
     * @param array $parameters 
     * @return bool
     */
    public static function __callStatic( $method, $parameters ) {
      $class = __CLASS__."\\{$method}";
      return call_user_func_array(
        array( $class, 'test' ),
        $parameters
      );
    }
    
  }
  
} ?>