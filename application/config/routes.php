<?php
    defined('BASEPATH') OR exit('No direct script access allowed');
    // Admin Routs
    $route['Management'] = SPECIALPATH;
    $route['Management/Login'] = sprintf('%s/Profiles/Login',SPECIALPATH);
    $route['Management/LoginProcess'] = sprintf('%s/Profiles/ProcessLogin',SPECIALPATH);
    $route['Management/Logout'] = sprintf('%s/Profiles/Logout',SPECIALPATH);;


    $route['Management/(:any)'] = sprintf('%s/$1',SPECIALPATH);
    $route['Management/(:any)/(:any)'] = sprintf('%s/$1/$2',SPECIALPATH);
    $route['Management/(:any)/(:any)/(:any)'] = sprintf('%s/$1/$2/$3',SPECIALPATH);
    $route['Management/(:any)/(:any)/(:any)/(:any)'] = sprintf('%s/$1/$2/$3/$4',SPECIALPATH);
   

    $route['submitquote'] = 'Home/SubmitQuote';
    $route['category'] = 'Categories/Index';
    $route['category/(:any)'] = 'Categories/Index/$1';
    $route['product/(:any)'] = 'Products/Index/$1';
    $route['submitreview'] = 'Home/SubmitReview';
    $route['contact'] = 'Home/Contact';
    $route['contact/submit'] = 'Home/Contact/$1';
    $route['blog'] = 'Blogs/Index';
    $route['blog/(:any)'] = 'Blogs/Detail/$1';
    
    $route['(:any)/SetValidationRules'] = 'Home/NotFound';
    $route['(:any)/GetFields'] = 'Home/NotFound';
    $route['(:any)/ProcessPost'] = 'Home/NotFound';
    $route['(:any)/PartialView'] = 'Home/NotFound';
    $route['(:any)/View'] = 'Home/NotFound';
    $route['(:any)/PrepareList'] = 'Home/NotFound';
    $route['Fetch/(:any)'] = 'Json/$1';
    $route['default_controller'] = 'Home';
    $route['404_override'] = '';
    $route['translate_uri_dashes'] = FALSE;
    