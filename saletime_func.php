<?php
/* This function accepts two times and returns true if the current time falls
   between them and false otherwise.

   function saletime($sale_start, $sale_end[, $test_get])

   $sale_start    (int or string) [OPTIONAL, default: "OFF"]
     The start time either unix time integer or date string e.g. "2013-06-27 12:00:00"

   $sale_end      (int or string) [OPTIONAL, default: "NEVER"]
     The end time either unix time integer or date string e.g. "2013-06-30 12:00:00"

   $test_get      (string) [OPTIONAL, default: "testnow"]
     Add "?test=" and this value in the URL in order to return TRUE and debug to console
*/

/* Example 
  if ( saletime("2013-06-27 12:00:00", 1373914800) ) {
    //sale is on!
  } else {
    //sale is off!
  }
*/

/* Shortcuts

  // You can use words "NOW" and "NEVER" where they make sense
  // "OFF" passed as the first or only parameter forces FALSE

  saletime("OFF")                 // will always be false
  saletime()                      // will always be false. "OFF" is the default first param
  saletime("OFF", "Jan 2 2060")   // False, ignores the end date
  saletime("NOW", "Jan 2 2013")   // will true from now until until Jan 2 2013
  saletime("Jan 2 2013")          // will be true forever starting at Jan 2 2013 
  saletime("NOW", "NEVER")        // Will always be true
  saletime("NOW")                 // Also always true. "NEVER" is default second param

  // The exception to all of these is that passing the correct 
  // "?test="" value into the URL will ALWAYS return TRUE.
  // Pass FALSE as the third parameter to disable this behavior

 */

function saletime($sale_start = 'OFF', $sale_end = 'NEVER', $test_get = "testnow") {

  if (!$test_get)     {
      $test_get = "testnow";
  }

  $input_start = $sale_start;
  $input_end = $sale_end;

  $now_time=time();

  //OFF keyword
  if(strtoupper($sale_start == "OFF"))
      return FALSE;

  //NOW keyword
  if (strtoupper($sale_start) == "NOW") {
    $sale_start = $now_time - 1000;
  }

  //NEVER keyword
  if (strtoupper($sale_end) == "NEVER") {
    $sale_end = $now_time + (1 * 365 * 24 *60 * 60);
  }

  $date_format='d M Y H:i:s';

  //Figure out start time
  if (!is_int($sale_start))
      $sale_start_int = strtotime($sale_start);
  else
      $sale_start_int = $sale_start;

  //Figure out end time
  if (!is_int($sale_end))
    $sale_end_int = strtotime($sale_end);
  else
    $sale_end_int = $sale_end;

  //Debug to console on test
  if ($test_get != FALSE && $_GET['test'] == $test_get) {
      $console_txt = "Start time: " . date($date_format, $sale_start_int) . " [UNIX: ". $sale_start_int . "] [Input Value: '".$input_start."']\\n" .     "End time: ". date($date_format, $sale_end_int) . " [UNIX: ". $sale_end_int . "] [Input Value: '".$input_end."']\\n" .     "Current time: ". date($date_format, $now_time) . " [UNIX: ". $now_time . "]";
      echo "<script>if ( window.console && window.console.log ) {console.log(\"$console_txt\"); }</script>";
  }

  if ($_GET['test'] == $test_get || time() >= $sale_start_int && time() <= $sale_end_int) {
      return TRUE;
  } else {
      return FALSE;
  }
}

?>
