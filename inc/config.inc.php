<?php

// This constant points to our file
define('FILE_PATH', 'data/employees.csv');
// These constants based on data structure on csv file
define('FIRST_NAME', 0);
define('LAST_NAME', 1);
define('DATE_OF_BIRTH', 2);
define('GENDER', 3);
define('PHONE', 4);
define('JOB_TITLE', 5);
define('DEPARTMENT', 6);
// Number of data columns in csv
define('NUM_COLS', 7);
//column number of constructed email field
define('EMAIL', 7);
// Rank order ratio numbers use to implement selfRank interface in Employee class 
define('DIRECT_MATCH_RATIO', 256000);
define('PARTIAL_MATCH_RATIO', 192000);
define('WORD_MATCH_RATIO', 64000);
define('EMAIL_RATIO', 32000);
define('PHONE_RATIO', 16000);
define('FIRST_NAME_RATIO', 8000);
define('LAST_NAME_RATIO', 4000);
define('JOB_TITLE_RATIO', 2000);
define('DEPARTMENT_RATIO', 1000);
define('MAX_MATCH_COUNT',1000);

?>