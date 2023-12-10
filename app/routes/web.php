<?php
	
	$routes = [];

	$controller = '/ForgetPasswordController';
	$routes['forget-pw'] = [
		'index' => $controller.'/index',
		'edit' => $controller.'/edit',
		'create' => $controller.'/create',
		'delete' => $controller.'/destroy',
		'send'   => $controller.'/send',
		'resetPassword' => $controller .'/resetPassword '
	];

	_routeInstance('dashboard', 'Dashboard', $routes);
	_routeInstance('user', 'UserController', $routes, [
		'edit-credentials' => 'editCredentials',
		'profile' => 'profile'
	]);
	_routeInstance('position', 'PositionController', $routes);
	_routeInstance('department', 'DepartmentController', $routes);
	_routeInstance('schedule', 'ScheduleController', $routes);
	_routeInstance('admin-shift', 'AdminShiftController', $routes);
	_routeInstance('payroll', 'PayrollController', $routes);
	_routeInstance('leave', 'LeaveController', $routes);
	_routeInstance('attendance', 'AttendanceController', $routes, [
		'approval' => 'approval'
	]);

	_routeInstance('attachment', 'Attachment', $routes);
	_routeInstance('deduction', 'DeductionController', $routes, [
		'deduction' => 'deductions'
	]);


	$routes['auth'] = [
		'logout' => '/Logout/index',
		'login'  => '/Login/index'
	];
	return $routes;
?>