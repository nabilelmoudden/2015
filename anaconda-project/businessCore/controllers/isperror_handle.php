<?php
 ini_set('display_errors', 0);
 ini_set("error_log", "./isptest.log");

function errorHandler($errno, $errstr, $errfile, $errline)
{  
  switch ($errno) {
    case E_ERROR:
      // Send an e-mail to the administrator
      error_log("Error: $errstr \n Fatal error on line $errline in file $errfile \n");
 
      // Write the error to our log file
      error_log("Error: $errstr \n Fatal error on line $errline in file $errfile \n");
      break;
 
    case E_WARNING:
      // Write the error to our log file
      error_log("Warning: $errstr \n in $errfile on line $errline \n");
      break;
 
    case E_NOTICE:
      // Write the error to our log file
      error_log("Notice: $errstr \n in $errfile on line $errline \n");
      break;
    case E_PARSE:
      // Write the error to our log file
      error_log("PARSE: $errstr \n in $errfile on line $errline \n");
      break;
 
    default:
      // Write the error to our log file
      error_log("Unknown error [#$errno]: $errstr \n in $errfile on line $errline \n");
      break;
  }
 
  // Don't execute PHP's internal error handler
  return TRUE;
}
set_error_handler('errorHandler');