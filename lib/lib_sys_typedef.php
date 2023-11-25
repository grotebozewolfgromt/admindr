<?php
/**
 * In this library exist only type related functions, such as type conversion
 *
 * IMPORTANT:
 * This library is language independant, so don't use language specific element
 *
 * 4 april 2019: split lib_types into lib_types and lib_typedef
 * 
 * @author Dennis Renirie
 */


//include_once(GLOBAL_PATH_LOCAL_CMS_LIBRARIES.DIRECTORY_SEPARATOR.'lib_date.php');
//include_once(GLOBAL_PATH_LOCAL_CMS_LIBRARIES.DIRECTORY_SEPARATOR.'lib_file.php');
//include_once(GLOBAL_PATH_LOCAL_CMS_LIBRARIES.DIRECTORY_SEPARATOR.'lib_img.php'); 
//include_once(GLOBAL_PATH_LOCAL_CMS_LIBRARIES.DIRECTORY_SEPARATOR.'lib_inet.php');
//include_once(GLOBAL_PATH_LOCAL_CMS_LIBRARIES.DIRECTORY_SEPARATOR.'lib_math.php');
//include_once(GLOBAL_PATH_LOCAL_CMS_LIBRARIES.DIRECTORY_SEPARATOR.'lib_misc.php');
//include_once(GLOBAL_PATH_LOCAL_CMS_LIBRARIES.DIRECTORY_SEPARATOR.'lib_string.php');



define('CHARSET_UTF8', 'UTF-8');//the mb_detect_encoding function returns this value when UTF8


/**
 * Extra defined datatypes
 * 
 * Soms is het handig om een datatype te kunnen definieren (detecteren is niet altijd een haalbare optie)
 * zoals bijvoorbeeld als parameter in een functie
 * 
 * prefix TP_ stands for TyPe. the prefix T_ is already used by PHP self
 * prefix CT_ stands for Column Type, because it is explicitly used in databases
 * the reason for the different prefixes is the database can have different types than php, for example TP_INTEGER is always 64 bits, while CT_INTEGER means 32 bits
 */

//prefix TP_ (general php)
define('TP_UNDEFINED', 0);//use this as default parameter when no type is defined
define('TP_STRING', 100);//can't be 0 because of default parameters
define('TP_INTEGER', 1); // 64 bits integer
define('TP_BOOLEAN', 2); 
define('TP_BOOL', TP_BOOLEAN); //alias for boolean
define('TP_DOUBLE', 3);
define('TP_FLOAT', TP_DOUBLE); //alias for double
define('TP_BINARY', 4);
define('TP_DATETIME', 5);
define('TP_ARRAY', 6); 
define('TP_OBJECT', 7); 
define('TP_DECIMAL', 8); //decimaal is preciezer dan een float. de klasse TDecimalAbstract handelt dit type af
define('TP_CURRENCY', 9); // voor geld


//prefix CP_ (db only)
//defining the database column-types (php kent heel veel van deze types niet, zo is er geen auto increment, geen enum, verschil tussen 32 en 64 bits integer  )
define('CT_VARCHAR', 10);//max 265 characters
define('CT_INTEGER', 11);
define('CT_INTEGER64', TP_INTEGER);
define('CT_LONGTEXT', TP_STRING); //varchar with unlimited amount of char
define('CT_FLOAT', 12);
define('CT_DOUBLE', TP_DOUBLE);
define('CT_BLOB', TP_BINARY);
define('CT_ENUM', 13);
define('CT_BOOL', TP_BOOLEAN);
define('CT_DATETIME', TP_DATETIME);
define('CT_AUTOINCREMENT', 14); //voor databases zoals Access is dit een apart datatype
define('CT_DECIMAL', TP_DECIMAL); //decimal is preciezer dan een float (float heeft last van afrondingen ivm bit representatie)
define('CT_CURRENCY', TP_CURRENCY); //datatype voor geld 

//time related
define('MINUTE_IN_SECS', 60);
define('HOUR_IN_SECS', 3600);//60*60
define('DAY_IN_SECS', 86400);//60*60*24
define('WEEK_IN_SECS', 604800);//60*60*24*7
define('YEAR_IN_SECS', 31536000);//60*60*24*365

//define standard lengths
define('LENGTH_STRING_IPV6', 50);//As indicated a standard ipv6 address is at most 45 chars, but an ipv6 address can also include an ending % followed by a "scope" or "zone" string, which has no fixed length but is generally a small positive integer or a network interface name, so in reality it can be bigger than 45 characters. Network interface names are typically "eth0", "eth1", "wlan0", so choosing 50 as the limit is likely good enough.
define('LENGTH_STRING_IPV4', 15);//192,168,000,000
define('LENGTH_STRING_MD5', 32);//79054025255fb1a26e4bc422aef54eb4

//define chars uniqueid
define('UNIQUEID_CHARS', 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789_'); //these MUST be sql/url injection safe characters

//common used regular expressions -->predefined types for telephone number, zipcodes etc on http://search.cpan.org/dist/Regexp-Common/
define('REGEX_NUMERIC', '01234567890');
define('REGEX_NUMERIC_NEGATIVEFLOAT', REGEX_NUMERIC.'\.\-');
define('REGEX_HEXADECIMAL', '01234567890ABCDEFabcdef');
define('REGEX_ALPHABETICAL', 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ');
define('REGEX_DATES_NUMERIC', REGEX_NUMERIC.' \.\-\/');//internationale numeric dates have a dot (.), a dash (-), or a slash (/);
define('REGEX_ALPHANUMERIC', REGEX_ALPHABETICAL.REGEX_NUMERIC);
define('REGEX_ALPHANUMERIC_NEGATIVEFLOAT', REGEX_ALPHABETICAL.REGEX_NUMERIC_NEGATIVEFLOAT);
define('REGEX_ALPHANUMERIC_SPACE', REGEX_ALPHABETICAL.REGEX_NUMERIC.' ');
define('REGEX_ALPHANUMERIC_UNDERSCORE', REGEX_ALPHABETICAL.REGEX_NUMERIC.'_');
define('REGEX_ALPHANUMERIC_UNDERSCORE_MINUS', REGEX_ALPHANUMERIC_UNDERSCORE.'\-');
define('REGEX_LATIN', '\p{Latin}'); //expand please for more language support
define('REGEX_INTERPUNCTION', '¡!\?“”‘’‟\.,‚„\'"′″´˝^`:;&_­¦\|\/\-\+‒~\*\@# '.'\\\\');
define('REGEX_PARENTHESES', '<>\(\)\[\]\{\}');
define('REGEX_CURRENCYSYMBOLS', '\$€¥£');
define('REGEX_CONTROLCHARACTERS_HARMLESS', '\n\t');
define('REGEX_READINGSYMBOLS', REGEX_INTERPUNCTION . REGEX_PARENTHESES . REGEX_CURRENCYSYMBOLS);
define('REGEX_TEXT_NORMAL', REGEX_LATIN . REGEX_NUMERIC . REGEX_READINGSYMBOLS . REGEX_CONTROLCHARACTERS_HARMLESS);
define('REGEX_TEXT_SIMPLE', REGEX_ALPHANUMERIC_UNDERSCORE_MINUS . REGEX_CONTROLCHARACTERS_HARMLESS . '\.\?\! :;\/' . '\\\\');

//filters for filter_varext() in lib_string
//define our custom (extended) filters
//we use numbers so the switch statement is faster
define('FILTEREXT_SANITIZE_CLASS', 		    9991);
define('FILTEREXT_SANITIZE_FUNCTION', 		9992);
define('FILTEREXT_SANITIZE_FILE', 		    9993);
define('FILTEREXT_SANITIZE_DIRECTORY', 		9994);
define('FILTEREXT_SANITIZE_URL_FILE', 		9995);
define('FILTEREXT_SANITIZE_URL_DIRECTORY', 	9996);
define('FILTEREXT_SANITIZE_URL_STRICT', 	9997);//strict means: we dont accept funny business like $-_.+!*'(),{}|\\^~[]`<>#%";?@&=.


//url safe (!!!) comparison operators. prefixed because you can translate them. translation of LIKE-operator maybe 'contains', translating like in a normal translation would be 'i love it'
define('COMPARISON_OPERATOR_EQUAL_TO',              'compare_equal_to'); 	// =
define('COMPARISON_OPERATOR_NOT_EQUAL_TO', 		    'compare_not_equal_to'); // !=
define('COMPARISON_OPERATOR_IS', 			        'compare_is'); 			// in sql: operator 'IS' is used for comparing with NULL or NOT NULL. i.e. 'is NULL' or 'is TRUE' --> http://www.sql.org/sql-database/postgresql/manual/functions-comparison.html
define('COMPARISON_OPERATOR_IS_VALUE_NULL', 		'compare_is_value_null'); //
define('COMPARISON_OPERATOR_IS_VALUE_NOTNULL',      'compare_is_value_notnull'); // 
define('COMPARISON_OPERATOR_IN', 			        'compare_in'); 	//SELECT first_name, last_name, subject FROM student_details WHERE games IN ('Cricket', 'Football');
define('COMPARISON_OPERATOR_NOT_IN', 			    'compare_not_in'); 	//SELECT first_name, last_name, subject FROM student_details WHERE games NOT IN ('Cricket', 'Football');
define('COMPARISON_OPERATOR_LESS_THAN', 		    'compare_less_than');	// <
define('COMPARISON_OPERATOR_LESS_THAN_OR_EQUAL_TO', 'compare_less_than_or_equal_to');// <=
define('COMPARISON_OPERATOR_GREATER_THAN', 		    'compare_greater_than');	// >
define('COMPARISON_OPERATOR_GREATER_THAN_OR_EQUAL_TO',  'compare_less_than');// >=;
define('COMPARISON_OPERATOR_LIKE', 			        'compare_contains');// LIKE -- minder nauwkeurig dan equal to (handig voor zoeken in databases)
define('COMPARISON_OPERATOR_BETWEEN',               'compare_between');// BETWEEN (2 waardes)


define('COMPARISON_OPERATOR_EQUAL_TO_TRANSLATIONDEFAULT',                   'equal to'); 	// =
define('COMPARISON_OPERATOR_NOT_EQUAL_TO_TRANSLATIONDEFAULT',               'not equal to'); // !=
define('COMPARISON_OPERATOR_IS_TRANSLATIONDEFAULT',                         'is'); 			// in sql: operator 'IS' is used for comparing with NULL or NOT NULL. i.e. 'is NULL' or 'is TRUE' --> http://www.sql.org/sql-database/postgresql/manual/functions-comparison.html
define('COMPARISON_OPERATOR_IS_VALUE_NULL_TRANSLATIONDEFAULT',              'is null'); //
define('COMPARISON_OPERATOR_IS_VALUE_NOTNULL_TRANSLATIONDEFAULT',           'is not null'); // 
define('COMPARISON_OPERATOR_IN_TRANSLATIONDEFAULT',                         'in'); 	//SELECT first_name, last_name, subject FROM student_details WHERE games IN ('Cricket', 'Football');
define('COMPARISON_OPERATOR_NOT_IN_TRANSLATIONDEFAULT',                     'not in'); 	//SELECT first_name, last_name, subject FROM student_details WHERE games NOT IN ('Cricket', 'Football');
define('COMPARISON_OPERATOR_LESS_THAN_TRANSLATIONDEFAULT',                  'less than');	// <
define('COMPARISON_OPERATOR_LESS_THAN_OR_EQUAL_TO_TRANSLATIONDEFAULT',      'less than or equal to');// <=
define('COMPARISON_OPERATOR_GREATER_THAN_TRANSLATIONDEFAULT',               'greater than');	// >
define('COMPARISON_OPERATOR_GREATER_THAN_OR_EQUAL_TO_TRANSLATIONDEFAULT',   'less than');// >=;
define('COMPARISON_OPERATOR_LIKE_TRANSLATIONDEFAULT',                       'contains');// LIKE -- minder nauwkeurig dan equal to (handig voor zoeken in databases)
define('COMPARISON_OPERATOR_BETWEEN_TRANSLATIONDEFAULT',                    'between');// BETWEEN (2 waardes)



//url safe (!!!) logical operators. prefixed because they can be uniquely translated 
define('LOGICAL_OPERATOR_AND', 				'logic_and');
define('LOGICAL_OPERATOR_OR', 				'logic_or');
define('LOGICAL_OPERATOR_XOR', 				'logic_exclusive_or'); //$a xor $b	Xor	TRUE if either $a or $b is TRUE, but not both.
define('LOGICAL_OPERATOR_NOT', 				'logic_not');

//url safe (!!!) sort orders
define('SORT_ORDER_ASCENDING', 	'ASC');
define('SORT_ORDER_DESCENDING', 'DESC');
define('SORT_ORDER_NONE', 	''); //no sort order 

//url safe (!!!) join types
define('JOIN_INNER', 	'join_inner');
define('JOIN_OUTER', 	'join_outer');
define('JOIN_LEFT', 	'join_left');
define('JOIN_RIGHT', 	'join_right');


//url safe (!!!)  standard values for bulk actions
define('BULKACTION_VARIABLE_CHECKBOX_RECORDID','chkRecordID'); //names of the checkboxes
define('BULKACTION_VARIABLE_SELECT_ACTION','selBulkAction'); //names of the select boxes
define('BULKACTION_VALUE_DELETE','delete');
define('BULKACTION_VALUE_DUPLICATE','duplicate');
define('BULKACTION_VALUE_CHECKOUT','checkout');
define('BULKACTION_VALUE_CHECKIN','checkin');
define('BULKACTION_VALUE_EXPORTCSV','exportcsv');
define('BULKACTION_VALUE_EXPORTHTML','exporthtml');
define('BULKACTION_VALUE_LOCK','lockrecord');
define('BULKACTION_VALUE_UNLOCK','unlockrecord');

//authorize constants (also for cms, although it would probably be better to move them to boostrap_cms_auth)
define('AUTH_RESOURCESEPARATOR','/'); //for example: books/authors/delete
define('SESSIONARRAYKEY_PERMISSIONS','sPeRePa'); //$_SESSION[SESSIONARRAYKEY_AUTH]: this is a weird value because of security reasons (stands for "permissions resource paths")

define('AUTH_OPERATION_DELETE', 'delete');
define('AUTH_OPERATION_CREATE', 'create');
define('AUTH_OPERATION_CHANGE', 'change');
define('AUTH_OPERATION_VIEW', 'view');
define('AUTH_OPERATION_EXECUTE', 'execute');
define('AUTH_OPERATION_CHECKINOUT', 'check-in check-out');//needs to be variable-safe (space is replaced by dash (-))
define('AUTH_OPERATION_LOCKUNLOCK', 'lock unlock');//needs to be variable-safe (space is replaced by dash (-))
define('AUTH_OPERATION_CHANGEORDER', 'change order');//needs to be variable-safe (space is replaced by dash (-))
// define('AUTH_OPERATION_CHANGESETTINGS', 'change settings');//needs to be variable-safe (space is replaced by dash (-))

define('AUTH_MODULE_CMS', 'cms');// what represents the system in the authorise resources? (that part of the resource is called "module"). for example: cms/settings/view

define('AUTH_CATEGORY_SYSSETTINGS', 'system settings');// what represents the system in the authorise resources? (that part of the resource is called "module"). for example: cms/settings/view
define('AUTH_OPERATION_SYSSETTINGS_VIEW', 'view system settings');//view settings //needs to be variable-safe (space is replaced by dash (-))
// define('AUTH_OPERATION_SYSSETTINGS_VIEWSYSTEM', 'view system');//view settings of user itself //needs to be variable-safe (space is replaced by dash (-))
// define('AUTH_OPERATION_SYSSETTINGS_VIEWUSER', 'view user');//view settings of user itself //needs to be variable-safe (space is replaced by dash (-))
// define('AUTH_OPERATION_SYSSETTINGS_CHANGESYSTEM', 'change system');//change settings system-wide //needs to be variable-safe (space is replaced by dash (-))
// define('AUTH_OPERATION_SYSSETTINGS_CHANGEUSER', 'change user');//change settings of the user itself //needs to be variable-safe (space is replaced by dash (-))

define('AUTH_CATEGORY_SYSSITES', 'websites-top-screen');// the "sites" listing on the left side of the screen
define('AUTH_OPERATION_SYSSITES_VISIBILITY', 'visible'); //view websites right side of screen
define('AUTH_OPERATION_SYSSITES_SWITCH', 'able to switch site'); //able to change sites

define('AUTH_CATEGORY_MODULEACCESS', '__MODULE__'); //the general access to a module is a separate permission //needs to be variable-safe (space is replaced by dash (-))
define('AUTH_OPERATION_MODULEACCESS', 'access'); //the general access to a module is a separate permission




//settings
define('SETTINGS_RESOURCESEPARATOR','/'); //for example: books/authors/delete
define('SESSIONARRAYKEY_SETTINGS','sSeRePa'); //$_SESSION[SESSIONARRAYKEY_SETTINGS]: this is a weird value because of security reasons (stands for "resource paths")

define('SETTINGS_MODULE_CMS', 'cms');// what represents the system in the settings resources? (that part of the resource is called "module"). for example: cms/query_limit_default
define('SETTINGS_MODULE_SYSTEM', 'system');// what represents the system in the settings resources? (that part of the resource is called "module"). for example: cms/query_limit_default

define('SETTINGS_CMS_MEMBERSHIP_ANYONECANREGISTER', 'anyone_can_register');
define('SETTINGS_CMS_MEMBERSHIP_NEWUSER_ROLEID', 'new_user_roleid');
define('SETTINGS_CMS_MEMBERSHIP_USERPASSWORDEXPIRES_DAYS', 'user_password_expires_days');
define('SETTINGS_CMS_PAGINATOR_MAXRESULTSPERPAGE', 'paginator_maxresults_page');
define('SETTINGS_CMS_SYSTEMMAILBOT_FROM_EMAILADDRESS', 'systemmailbot_from_emailaddress');
define('SETTINGS_CMS_SYSTEMMAILBOT_FROM_NAME', 'systemmailbot_from_name');
define('SETTINGS_SYSTEM_EMAILSYSADMIN', 'email_sysadmin');

//url safe (!!!)  standard variablenames + values for other many used actions
define('ACTION_VARIABLE_ID', 'id'); //if you want to pass an id to another page, use this constant, so it's the same on every page
define('ACTION_VARIABLE_UNIQUEID', 'uid'); //if you want to pass a id to another page that is not the regular id, use this constant, so it's the same on every page
define('ACTION_VARIABLE_PARENTID', 'pid'); //if you want to pass a parent id (i.e. you want to create a new translation of an existing record - existing record is parentid) to another page, use this constant, so it's the same on every page
define('ACTION_VARIABLE_CANCEL', 'cancel'); //if you want to pass a cancel to another page, use this constant, so it's the same on every page
define('ACTION_VALUE_CANCEL', '1'); //if you want to pass a cancel to another page, use this constant, so it's the same on every page
define('ACTION_VARIABLE_LANGUAGEID', 'lid'); //if you want to pass a languageid to another page, use this constant, so it's the same on every page
define('ACTION_VARIABLE_DELETE', 'delete'); //if you want to pass a delete-action to another page, use this constant, so it's the same on every page

define('ACTION_VARIABLE_ORDERONEUPDOWN', 'changeOrderOneUpDown');
define('ACTION_VALUE_ORDERONEUPDOWN', '1'); //enable change updown

define('ACTION_VARIABLE_ORDERONEUP', 'changeUp'); 
define('ACTION_VALUE_ORDERONEUP', '1'); 
define('ACTION_VALUE_ORDERONEDOWN', '0'); 

define('ACTION_VARIABLE_SORTORDER', 'sortorder'); 
define('ACTION_VARIABLE_SORTCOLUMNINDEX', 'sortorderindex'); //because passing fieldnames via url is dangerous, we use an index we can check against numeric to prevent sql injection


//default commonly used translation keys (those are KEYS, NOT TRANSLATIONS -> these keys you feed into the transc(), transs() or transm() functions)
define('TRANS_MODULENAME_MENU', 'modulename_menu'); //needs to be short
define('TRANS_MODULENAME_TITLE', 'modulename_title'); //can be longer
define('TRANS_DETAILSAVE_EDITRECORD_TITLE', 'detailsave_title_editrecord'); //super generic edit record
define('TRANS_DETAILSAVE_CREATERECORD_TITLE', 'detailsave_title_createrecord'); //super generic create record


//Mime types
define('MIME_TYPE_OCTETSTREAM', 'application/octet-stream');
define('MIME_TYPE_ZIP', 'application/zip');
define('MIME_TYPE_TEXT', 'text/plain');
define('MIME_TYPE_HTML', 'text/html');
define('MIME_TYPE_MULTIPART_ALTERNATIVE', 'multipart/alternative');
define('MIME_TYPE_MULTIPART_MIXED', 'multipart/mixed');
define('MIME_TYPE_MULTIPART_RELATED', 'multipart/related');


//Operating Systems
define('OS_WINDOWS', 'Windows');
define('OS_IPAD', 'iPad');
define('OS_IPOD', 'iPod');
define('OS_IPHONE', 'iPhone');
define('OS_MAC', 'Mac');
define('OS_ANDROID', 'Android');
define('OS_LINUX', 'Linux');
define('OS_NOKIA', 'Nokia');
define('OS_BLACKBERRY', 'BlackBerry');
define('OS_FREEBSD', 'FreeBSD');
define('OS_OPENBSD', 'OpenBSD');
define('OS_NETBSD', 'NetBSD');
define('OS_OPENSOLARIS', 'OpenSolaris');
define('OS_SUNOS', 'SunOS');
define('OS_OS2', 'OS\/2');
define('OS_BEOS', 'BeOS');

//Browsers
define('BROWSER_CHROME', 'Chrome');
define('BROWSER_FIREFOX', 'Firefox');
define('BROWSER_EDGE', 'Edge');
define('BROWSER_OPERA', 'Opera');
define('BROWSER_SAFARI', 'Safari');
define('BROWSER_LYNX', 'Lynx');
define('BROWSER_INTERNETEXPLORER', 'Lynx');
define('BROWSER_GOOGLEBOT', 'Google Bot');

//encryption methods
define('ENCRYPTION_CYPHERMETHOD_AES256CBC', 'aes-256-cbc');
define('ENCRYPTION_CYPHERMETHOD_DEFAULT', ENCRYPTION_CYPHERMETHOD_AES256CBC);
define('ENCRYPTION_DIGESTALGORITHM_SHA3512', 'sha3-512');
define('ENCRYPTION_DIGESTALGORITHM_SHA512', 'sha512');
define('ENCRYPTION_DIGESTALGORITHM_DEFAULT', ENCRYPTION_DIGESTALGORITHM_SHA512);

//misc
define('INSTALLED_POSTFIX', '____INSTALLED____'); //this is used for example for themes to mark directory names of the currently installed theme


//google api
define('SESSIONARRAYKEY_GOOGLEAPI_TOKEN', 'googleapitoken');

?>
