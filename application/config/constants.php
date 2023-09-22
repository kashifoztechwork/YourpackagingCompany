<?php
defined('BASEPATH') OR exit('No direct script access allowed');
//date_default_timezone_set('Asia/Karachi');
//System Defined Constants
defined('SHOW_DEBUG_BACKTRACE') OR define('SHOW_DEBUG_BACKTRACE', TRUE);
defined('FILE_READ_MODE')  OR define('FILE_READ_MODE', 0644);
defined('FILE_WRITE_MODE') OR define('FILE_WRITE_MODE', 0666);
defined('DIR_READ_MODE')   OR define('DIR_READ_MODE', 0755);
defined('DIR_WRITE_MODE')  OR define('DIR_WRITE_MODE', 0755);
defined('FOPEN_READ')   OR define('FOPEN_READ', 'rb');
defined('FOPEN_READ_WRITE')   OR define('FOPEN_READ_WRITE', 'r+b');
defined('FOPEN_WRITE_CREATE_DESTRUCTIVE') OR define('FOPEN_WRITE_CREATE_DESTRUCTIVE', 'wb'); // truncates existing file data, use with care
defined('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE')  OR define('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE', 'w+b'); // truncates existing file data, use with care
defined('FOPEN_WRITE_CREATE') OR define('FOPEN_WRITE_CREATE', 'ab');
defined('FOPEN_READ_WRITE_CREATE')  OR define('FOPEN_READ_WRITE_CREATE', 'a+b');
defined('FOPEN_WRITE_CREATE_STRICT')   OR define('FOPEN_WRITE_CREATE_STRICT', 'xb');
defined('FOPEN_READ_WRITE_CREATE_STRICT') OR define('FOPEN_READ_WRITE_CREATE_STRICT', 'x+b');
defined('EXIT_SUCCESS')  OR define('EXIT_SUCCESS', 0); // no errors
defined('EXIT_ERROR') OR define('EXIT_ERROR', 1); // generic error
defined('EXIT_CONFIG')   OR define('EXIT_CONFIG', 3); // configuration error
defined('EXIT_UNKNOWN_FILE')   OR define('EXIT_UNKNOWN_FILE', 4); // file not found
defined('EXIT_UNKNOWN_CLASS')  OR define('EXIT_UNKNOWN_CLASS', 5); // unknown class
defined('EXIT_UNKNOWN_METHOD') OR define('EXIT_UNKNOWN_METHOD', 6); // unknown class member
defined('EXIT_USER_INPUT')  OR define('EXIT_USER_INPUT', 7); // invalid user input
defined('EXIT_DATABASE') OR define('EXIT_DATABASE', 8); // database error
defined('EXIT__AUTO_MIN')   OR define('EXIT__AUTO_MIN', 9); // lowest automatically-assigned error code
defined('EXIT__AUTO_MAX')   OR define('EXIT__AUTO_MAX', 125); // highest automatically-assigned error code

//Custom Constants
defined('BASE') OR define('BASE',str_replace('index.php','',$_SERVER['SCRIPT_NAME']));
$Defaults = array(
    //'HTTP_HOST'=>'localhost',
    'REQUEST_SCHEME'=>'https',
    'SERVER_PORT'=>'443'
);
//If Accessing From CLI
if(!isset($_SERVER['REQUEST_SCHEME'])){
    $_SERVER = array_merge($_SERVER,$Defaults);
}
defined('PROTOCOL') OR define('PROTOCOL',$_SERVER['REQUEST_SCHEME']);
defined('HOST') OR define('HOST',$_SERVER['HTTP_HOST']);
defined('PORT') OR define('PORT',$_SERVER['SERVER_PORT']);
defined('SPECIALURLPREFIX') OR define('SPECIALURLPREFIX','Management');
defined('SPECIALPATH') OR define('SPECIALPATH','Admin');
defined('ACTIVITY_INSERT') OR define('ACTIVITY_INSERT','NewRecord');
defined('ACTIVITY_UPDATE') OR define('ACTIVITY_UPDATE','UpdateRecord');
defined('ACTIVITY_DELETE') OR define('ACTIVITY_DELETE','DeleteRecord');
defined('ACTIVITY_SELECT') OR define('ACTIVITY_SELECT','SelectRecord');
defined('DATEFORMAT') OR define('DATEFORMAT','Y-m-d');
defined('TIMEFORMAT') OR define('TIMEFORMAT','H:i:s');
defined('DATETIMEFORMAT') OR define('DATETIMEFORMAT','Y-m-d H:i:s');
defined('DATENOW') OR define('DATENOW',date(DATEFORMAT));
defined('DATETIMENOW') OR define('DATETIMENOW',date(DATETIMEFORMAT));
defined('TIMENOW') OR define('TIMENOW',date(TIMEFORMAT));
defined('ABS') OR define('ABS',str_replace('index.php','',$_SERVER['SCRIPT_FILENAME']));
defined('RSRC') OR define('RSRC','resources/');
defined('JS') OR define('JS','resources/js/');
defined('CSS') OR define('CSS','resources/css/');
defined('IMG_UPLOAD_PATH') OR define('IMG_UPLOAD_PATH',RSRC.'upload/img/');
defined('DOC_UPLOAD_PATH') OR define('DOC_UPLOAD_PATH',RSRC.'upload/doc/');
defined('OTHER_UPLOAD_PATH') OR define('OTHER_UPLOAD_PATH',RSRC.'upload/other/');

//Setting Current IP
$RemoteAddress = $_SERVER['REMOTE_ADDR'];
defined('MYIP') OR define('MYIP',$RemoteAddress == '::1' ? '127.0.0.1' : $RemoteAddress);

//Database Tables
defined('DB_MODULE') OR define('DB_MODULE','modules');
defined('DB_ACCESS') OR define('DB_ACCESS','accessmodules');
defined('DB_LOG') OR define('DB_LOG','logs');
defined('DB_ACTIVITY') OR define('DB_ACTIVITY','activities');
defined('DB_COMMENT') OR define('DB_COMMENT','comments');
defined('DB_PROFILE') OR define('DB_PROFILE','profiles');
defined('DB_ROLE') OR define('DB_ROLE','roles');
defined('DB_OPPORTUNITY') OR define('DB_OPPORTUNITY','opportunities');
defined('DB_DATA') OR define('DB_DATA','data');

defined('DB_SUPPLIER') OR define('DB_SUPPLIER','suppliers');
defined('DB_SUPPLIER_CONTACT') OR define('DB_SUPPLIER_CONTACT','suppliercontacts');

defined('DB_ORDER') OR define('DB_ORDER','orders');
defined('DB_ORDER_BATCH') OR define('DB_ORDER_BATCH','orderbatches');
defined('DB_PAYMENT') OR define('DB_PAYMENT','payments');
defined('DB_SHIPMENT') OR define('DB_SHIPMENT','shipments');
defined('DB_SHIPMENT_ITEM') OR define('DB_SHIPMENT_ITEM','shipmentitems');
defined('DB_BRAND') OR define('DB_BRAND','brands');
defined('DB_PRODUCT_MODEL') OR define('DB_PRODUCT_MODEL','productmodels');
defined('DB_PRODUCT_ITEM') OR define('DB_PRODUCT_ITEM','productitems');
defined('DB_PRODUCT_ITEM_TYPE') OR define('DB_PRODUCT_ITEM_TYPE','productitemtypes');
defined('DB_COMPANY') OR define('DB_COMPANY','companies');
defined('DB_WAREHOUSE') OR define('DB_WAREHOUSE','warehouses');
defined('DB_FREIGHT') OR define('DB_FREIGHT','freights');
defined('DB_CAMPAIGN') OR define('DB_CAMPAIGN','campaign');
defined('DB_CAMPAIGN_POOL') OR define('DB_CAMPAIGN_POOL','campaignpool');
defined('DB_CAMPAIGN_POOL_ACTIVITY') OR define('DB_CAMPAIGN_POOL_ACTIVITY','campaignpoolactivity');
defined('DB_CONTACT') OR define('DB_CONTACT','contacts');
defined('DB_INVOICE') OR define('DB_INVOICE','invoice');
// New Domain
defined('DB_CATEGORY') OR define('DB_CATEGORY','categories');
defined('DB_TAG') OR define('DB_TAG','tags');
defined('DB_SETTING') OR define('DB_SETTING','settings');   
defined('DB_REVIEW') OR define('DB_REVIEW','reviews');
defined('DB_QUICKQUOTE') OR define('DB_QUICKQUOTE','quickquote');
defined('DB_PRODUCT') OR define('DB_PRODUCT','products');
defined('DB_SLIDER') OR define('DB_SLIDER','sliders');
defined('DB_NAVIGATION') OR define('DB_NAVIGATION','navigations');
defined('DB_WIDGET') OR define('DB_WIDGET','widgets');
defined('DB_IMAGE') OR define('DB_IMAGE','images');
defined('BG_BLOG') OR define('DB_BLOG','blogs');
//Data
defined('DIMENTION_UNITS') OR define('DIMENTION_UNITS',['IN','CM','FT']);
defined('WEIGHT_UNITS') OR define('WEIGHT_UNITS',['LBS','KG','GR','ONS']);
defined('CURRENCIES') OR define('CURRENCIES',[
    'USD'=>['Symbol'=>'$','Position'=>'L'],
    'RMB'=>['Symbol'=>'짜','Position'=>'L'],
    'HKD'=>['Symbol'=>'HK$','Position'=>'L'],
    'AED'=>['Symbol'=>'AED','Position'=>'R']
]);

//Loading Template Constants
require_once 'template_constants.php';