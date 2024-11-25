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
	_routeInstance('payroll', 'PayrollController', $routes, [
		'view-payslip' => 'show_payslip' 
	]);
	_routeInstance('leave', 'LeaveController', $routes, [
		'approve' => 'approve',
		'admin-approval' => 'adminApproval',
		'summary' => 'leaveSummary',
		'user'    => 'userCredit'
	]);
	_routeInstance('leave-point', 'LeavePointController', $routes);
	_routeInstance('attendance', 'AttendanceController', $routes, [
		'approval' => 'approval',
		'import'   => 'import',
		'save-and-import' => 'saveAndImport',
		'logged-in' => 'loggedIn'
	]);

	_routeInstance('recruitment', 'RecruitmentController', $routes, [
		'create-account' => 'createEmployeeAccount'
	]);

	_routeInstance('payslip', 'PayslipController', $routes,[
		'delete' => 'delete'
	]);

	_routeInstance('recruitment-interviews', 'RecruitmentInterviewController', $routes);

	_routeInstance('deduction', 'DeductionController', $routes, [
		'deduction' => 'deductions'
	]);

	_routeInstance('holiday', 'HolidayController', $routes);

	_routeInstance('attachment', 'AttachmentController', $routes, [
		'delete' => 'delete',
		'update-visibility' => 'updateVisibility'
	]);

	_routeInstance('viewer', 'ViewerController', $routes,[
		'delete' => 'delete'
	]);

	_routeInstance('report', 'ReportController', $routes,[
		'review' => 'review'
	]);
	


	
	$routes['auth'] = [
		'logout' => '/Logout/index',
		'login'  => '/Login/index'
	];
	return $routes;
?>