<?php

namespace Config;

// Create a new instance of our RouteCollection class.
$routes = Services::routes();

/*
 * --------------------------------------------------------------------
 * Router Setup
 * --------------------------------------------------------------------
 */
$routes->setDefaultNamespace('App\Controllers');
$routes->setDefaultController('Home');
$routes->setDefaultMethod('index');
$routes->setTranslateURIDashes(false);
$routes->set404Override();
// The Auto Routing (Legacy) is very dangerous. It is easy to create vulnerable apps
// where controller filters or CSRF protection are bypassed.
// If you don't want to define all routes, please use the Auto Routing (Improved).
// Set `$autoRoutesImproved` to true in `app/Config/Feature.php` and set the following to true.
// $routes->setAutoRoute(false);

/*
 * --------------------------------------------------------------------
 * Route Definitions
 * --------------------------------------------------------------------
 */

// Home
$routes->get('/', 'Home::index');

// Login
$routes->get('/login', 'Login::index');
$routes->get('/Login', 'Login::index');

// Access Denied (for outsiders)
$routes->get('/accessDenied', 'AccessDenied::index');

// Student (Ajax) and Teacher (Ajax) are seperated so that access right can be controlled

// Student
$routes->get('/student/dashboard', 'Student::index');
$routes->get('/student/classroom/(:num)', 'Student::classroom/$1');
$routes->get('/student/assignment/(:num)', 'Student::assignment/$1');
$routes->get('/student/analysis', 'Student::analysis');

// Student Ajax
$routes->post('/student/questionUpdate', 'Student::questionUpdate');
$routes->post('/student/tagAdd', 'Student::tagAdd');
$routes->post('/student/tagDelete', 'Student::tagDelete');

// Teacher
$routes->get('/teacher/dashboard', 'Teacher::index');
$routes->get('/teacher/classroom/(:num)', 'Teacher::classroom/$1');
$routes->get('/teacher/assignment/(:num)', 'Teacher::assignment/$1');
$routes->get('/teacher/analysis', 'Teacher::analysis');

// Teacher Ajax
$routes->post('/teacher/assignmentAdd', 'Teacher::assignmentAdd');
$routes->post('/teacher/assignmentDelete', 'Teacher::assignmentDelete');
$routes->post('/teacher/assignmentEdit', 'Teacher::assignmentEdit');
$routes->post('/teacher/assignmentTopicUpdate', 'Teacher::assignmentTopicUpdate');
$routes->post('/teacher/questionAdd', 'Teacher::questionAdd');
$routes->post('/teacher/questionDelete', 'Teacher::questionDelete');
$routes->post('/teacher/questionUpdate', 'Teacher::questionUpdate');
$routes->post('/teacher/tagAdd', 'Teacher::tagAdd');
$routes->post('/teacher/tagDelete', 'Teacher::tagDelete');

// Admin
$routes->get('/admin/dashboard', 'Admin\Users::index');
$routes->get('/admin/classes', 'Admin\Classes::index');
$routes->get('/admin/classroom', 'Admin\Classroom::index');

// Admin ajax
$routes->get('/admin/users/loadUsers', 'Admin\Users::loadUsers');
$routes->post('/admin/users/addUser', 'Admin\Users::addUser');
$routes->post('/admin/users/deleteUser', 'Admin\Users::deleteUser');
$routes->post('/admin/users/editUser', 'Admin\Users::editUser');

$routes->get('/admin/classes/loadClasses', 'Admin\Classes::loadClasses');
$routes->post('/admin/classes/addClass', 'Admin\Classes::addClass');
$routes->post('/admin/classes/deleteClass', 'Admin\Classes::deleteClass');
$routes->post('/admin/classes/editClass', 'Admin\Classes::editClass');

$routes->get('/admin/classroom/loadUsers', 'Admin\Classroom::loadUsers');
$routes->get('/admin/classroom/getClassID', 'Admin\Classroom::getClassID');
$routes->get('/admin/classroom/getClassName', 'Admin\Classroom::getClassName');
$routes->post('/admin/classroom/addUser', 'Admin\Classroom::addUser');
$routes->post('/admin/classroom/deleteUser', 'Admin\Classroom::deleteUser');




/*
 * --------------------------------------------------------------------
 * Additional Routing
 * --------------------------------------------------------------------
 *
 * There will often be times that you need additional routing and you
 * need it to be able to override any defaults in this file. Environment
 * based routes is one such time. require() additional route files here
 * to make that happen.
 *
 * You will have access to the $routes object within that file without
 * needing to reload it.
 */
if (is_file(APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php')) {
    require APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php';
}
