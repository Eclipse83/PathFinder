Small Program to find the shortest distance from point A to point B

Usage:
  run composer install
  run command with valid inputs (SEE BELOW)

Arguments:
  <grid-json>  JSON 2D array of booleans, e.g. [[true,true],[true,false]]
  <start>      Start position as "row,col", e.g. 0,0
  <end>        End position as "row,col", e.g. 2,3

Example:
  MacOS/Linux: php run.php "[[true,true,true],[true,false,true],[true,true,true]]" "0,0" "2,2"
  Windows: php .\run.php "[[true,true,true],[true,false,true],[true,true,true]]" "0,0" "2,2"