<?php
/**
 * Routes configuration
 *
 * In this file, you set up routes to your controllers and their actions.
 * Routes are very important mechanism that allows you to freely connect
 * different URLs to chosen controllers and their actions (functions).
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.Config
 * @since         CakePHP(tm) v 0.2.9
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */
/**
 * Here, we are connecting '/' (base path) to controller called 'Pages',
 * its action called 'display', and we pass a param to select the view file
 * to use (in this case, /app/View/Pages/home.ctp)...
 */

	// for company
	Router::connect('/', array('controller' => 'pages', 'action' => 'display', 'home'));
	Router::connect('/companies/new', array('controller' => 'companies', 'action' => 'companyForm'));
	Router::connect('/companies/add', array('controller' => 'companies', 'action' => 'addCompany'));
	Router::connect('/companies/update', array('controller' => 'companies', 'action' => 'editCompany'));
	Router::connect('/companies/:id/edit', array('controller' => 'companies', 'action' => 'editCompanyForm'));
	Router::connect('/companies/:id/delete', array('controller' => 'companies', 'action' => 'deleteCompany'));
	Router::connect('/companies/:id', array('controller' => 'companies', 'action' => 'viewCompany'));
	Router::connect('/companies', array('controller' => 'companies', 'action' => 'index'));

	// for director
	Router::connect('/companies/:id/directors/new', array('controller' => 'companies', 'action' => 'directorForm'));
	Router::connect('/directors/add', array('controller' => 'companies', 'action' => 'addDirector'));
	Router::connect('/directors/update', array('controller' => 'companies', 'action' => 'editDirector'));
	Router::connect('/directors/:id/edit', array('controller' => 'companies', 'action' => 'editDirectorForm'));
	Router::connect('/directors/:id/delete', array('controller' => 'companies', 'action' => 'deleteDirector'));

	// for form
	Router::connect('/forms/form45/generate', array('controller' => 'forms', 'action' => 'generateForm45'));
	Router::connect('/forms/generate', array('controller' => 'forms', 'action' => 'generateForm'));
	Router::connect('/forms/choose/:id', array('controller' => 'forms', 'action' => 'chooseForm'));
	Router::connect('/forms/:id/preview', array('controller' => 'forms', 'action' => 'previewForm'));
	Router::connect('/forms/:id/download', array('controller' => 'forms', 'action' => 'downloadForm'));
	Router::connect('/forms/:id/delete', array('controller' => 'forms', 'action' => 'deleteForm'));
	
	//for function
        Router::connect('/api/functions/:id', array('controller' => 'FunctionCorps', 'action' => 'getFunction'));
        
        //for Secretary
        Router::connect('/api/companies/:id/secretaries', array('controller' => 'Secretaries', 'action' => 'getSecretaries'));
        //Router::connect('api/companies/:id/secretaries/',array());
        // api
	Router::connect('/api/companies/:id/directors', array('controller' => 'companies', 'action' => 'getDirectors'));
        //API forUsers
        Router::connect('/api/users/login',array('controller'=>'users','action'=>'login'));
/**
 * ...and connect the rest of 'Pages' controller's URLs.
 */
	Router::connect('/pages/*', array('controller' => 'pages', 'action' => 'display'));

/**
 * Load all plugin routes. See the CakePlugin documentation on
 * how to customize the loading of plugin routes.
 */
	CakePlugin::routes();

/**
 * Load the CakePHP default routes. Only remove this if you do not want to use
 * the built-in default routes.
 */
	require CAKE . 'Config' . DS . 'routes.php';
