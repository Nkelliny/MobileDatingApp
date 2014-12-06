<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	http://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There area two reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router what URI segments to use if those provided
| in the URL cannot be matched to a valid route.
|
*/

$route['default_controller'] = "welcome";
$route['404_override'] = '';
$route['at/show/(:any)']            = "at/show";
$route['at/(:any)']                 = "at";
$route['affiliates/manager']        = "affiliates";
$route['affiliates/member']         = "affiliates";
$route['affiliate/:num']            = "affiliate";
$route['join/qjoin']                = "join/qjoin";
$route['join/:num']                 = "join";
$route['profile/passchange']        = "profile/passchange";
$route['profile/dpic/(:any)']       = "profile/dpic";
$route['profile/mainpic/(:any)']    = "profile/mainpic";
$route['profile/savelang']          = "profile/savelang";
$route['profile/updatebio']         = "profile/updatebio";
$route['profile/updateinfo']        = "profile/updateinfo";
$route['profile/updatematch']       = "profile/updatematch";
$route['profile/preview']           = "profile/preview";
$route['profile/doupload']          = "profile/doupload";
$route['profile/profilepic']        = "profile/profilepic";
$route['profile/(:any)']            = "profile/show";
// app routes
$route['mapps/updateProfile/(:any)'] = "mapps/updateProfile";

// CHAT ROUTES //
$route['chat/checklogin/(:any)/cb/(:any)'] = "chat/checklogin";
$route['chat/grabuserdata/cb/(:any)'] = "chat/grabuserdata";
$route['chat/login/cb/(:any)'] = "chat/login";
//$route['profile/profilepic/(:any)'] = "profile/profilepic";

$route['uphotos/(:any)/:num']       = "uphotos/show";
$route['uphotos/(:any)']            = "uphotos";
$route['girls/:num'] = "girls";
$route['guys/:num'] = "guys";
$route['ladyboys/:num'] = "ladyboys";
$route['verify/resend'] = "verify/resend";
$route['verify/(:any)'] = "verify";
$route['thankyou/(:any)'] = "thankyou";
$route['join/m'] = "join";
$route['join/m/(:any)'] = "join";
$route['fblog/auth/:num'] = "fblog/auth";
//$route['dating/(:any)'] = "dating/show";
$route['login/pic'] = "login";
$route['mapps/test/frm/(:any)'] = "mapps/test";
$route['mapps/loadtextDroid/:num'] = "mapps/loadtextDroid";
// cache busting routes because android fucking sucks
$rout['mapps/appstart/:num'] = "mapps/appstart";
/* End of file routes.php */
/* Location: ./application/config/routes.php */