<?php 

    class NavigationHelper{
        
        public $navs = [];

        public function __construct()
        {
            $this->loadNavs();
            // $this->moduleRestrict();
        }

        public function getNavsHTML() {
            $navs = $this->navs;
            $retValHTML = '';
            if(!empty($navs)) {
                foreach($navs as $navGroupName => $navGroups) {
                    $retValHTML .= "<div class='sidebar-heading'>{$navGroupName}</div>";
                    foreach($navGroups as $navItem) {
                        $attributes = $navItem['attributes'];
                        $icon = empty($attributes['icon']) ? 'fas fa-fw fa-tachometer-alt': $attributes['icon'];
                        $retValHTML .= "<li class='nav-item'>
                        <a class ='nav-link pb-0' href='{$navItem['url']}'>
                            <i class='{$icon}'></i>
                            <span>{$navItem['label']}</span>
                        </a>
                      </li>";
                    }

                    $retValHTML .= "<hr class='sidebar-divider mt-3'>";
                }
            }
            return $retValHTML;
        }
        

        public function loadNavs() {
            $whoIs = whoIs();

            if($whoIs) {
                $moduleGroup = $this->moduleGroup();
                foreach($moduleGroup as $modKey => $modRow) {
                    $modRowItems = $modRow['items'];
                    foreach($modRowItems as $itemKey => $itemVal) {
                        $regExp = explode('|', $itemVal);
                        $url = _route("{$regExp[0]}:{$regExp[1]}");
                        $icon = $regExp[3] ?? '';

                        $this->addNavigation($modKey, $regExp[2], $url, [
                            'icon' => $icon
                        ]);
                    }
                }
            }
        }

        private function moduleRestrict() {
            $whoIs = whoIs();

            if($whoIs) {
                $userTypeAccess = $this->userModuleAccess();
                $modelGroup = $this->moduleGroup();

                if($userAccess = $userTypeAccess[strtolower($whoIs['type'])]) {

                    if(!is_array($userAccess)) {
                        $userAccess = self::LIST_OF_LINKS;
                    }
                    foreach($userAccess as $key => $row) {
                        foreach($modelGroup as $modKey => $modRow) {
                            $modRowItems = $modRow['items'];
                            foreach($modRowItems as $itemKey => $itemVal) {
                                $regExp = explode('|', $itemVal);
                                $url = _route("{$regExp[0]}:{$regExp[1]}");
                                $icon = $regExp[3] ?? '';

                                if(isEqual($key, $regExp[0])) {
                                    $this->addNavigation($modKey, $regExp[2], $url, [
                                        'icon' => $icon
                                    ]);
                                }
                            }
                        }
                    }
                }
            }
        }

        public function userModuleAccessTwo() {
            $access = [];
                $access['admin'] = '*';
                
                $access['regular_employee'] = [
                    'dashboard' => '*',
                    'payslip' => '*',
                    'attendance' => '*',
                    'leave' => '*'
                ];
                
                $access['payroll'] = [
                    'dashboard' => '*',
                    'payroll' => '*',
                    'deduction' => '*',
                ];

            return $access;
        }

        const LIST_OF_LINKS = [
            'dashboard' => '*',
            'user' => '*',
            'attendance' => '*',
            'position' => '*',
            'department' => '*',
            'admin-shift' => '*',
            'report' => '*',
            'payroll' => '*',
            'leave' => '*',
            'leave-point' => '*',
            'holiday'    => '*',
            'recruitment' => '*',
                'report' => '*'
        ];

        public function userModuleAccess() {
            $access = [
                'admin' => [
                    'dashboard' => '*',
                    'user' => '*',
                    'attendance' => '*',
                    'position' => '*',
                    'department' => '*',
                    'admin-shift' => '*',
                    'report' => '*'
                ],

                'superadmin' => [
                    'dashboard' => '*',
                    'user' => 'view',
                    'attendance' => '*',
                    'position' => 'view',
                    'department' => 'view',
                    'admin-shift' => 'view',
                    'report' => '*'
                ],
                
                'manager' => [
                    'dashboard' => '*',
                    'user' => 'view',
                    'attendance' => '*',
                    'position' => 'view',
                    'department' => 'view',
                    'admin-shift' => 'view',
                    'payroll' => 'view'
                ],

                'payroll' => [
                    'dashboard' => '*',
                    'payroll' => '*',
                    'deduction' => '*',
                ],

                'hr' => [
                    'dashboard' => '*',
                    'leave' => '*',
                    'leave-point' => '*',
                    'attendance' => '*',
                    'holiday'    => '*',
                    'recruitment' => '*',
                    'user' => '*',
                    'report' => '*'
                ],

                'regular_employee' => [
                    'dashboard' => '*',
                    'payslip' => '*',
                    'attendance' => '*',
                    'leave' => '*'
                ],
            ];

            return $access;
        }

        public function moduleGroup() {
            return [
                'main' => [
                    'label' => 'Main',
                    'items' => [
                        'dashboard|index|Dashboard'
                    ]
                ],
                'master' => [
                    'label' => 'Master',
                    'items' => [
                        'position|index|Position|fas fa-fw fa-rocket',
                        'department|index|Department|fas fa-fw fa-building',
                        'admin-shift|index|Schedule|fas fa-fw fa-calendar',
                        'user|index|Employee|fas fa-fw fa-user',
                        'deduction|index|Deductions|fas fa-fw fa-window-close',
                        'report|index|Report|fas fa-fw fa-chart-bar'
                    ]
                ],

                'hr' => [
                    'label' => 'HR',
                    'items' => [
                        'attendance|index|Attendance|fas fa-fw fa-clock',
                        'holiday|index|Holiday|fas fa-fw fa-window-close',
                        'payroll|index|Payroll|fa fa-fw fa-folder',
                        'leave|index|Leave|fa fa-fw fa-user-times',
                        'recruitment|index|Recruitment|fa fa-fw fa-user-plus'
                    ]
                ],

                'salary' => [
                    'label' => 'Salary',
                    'items' => [
                        'payslip|index|Payslip|fas fa-fw fa-folder'
                    ]
                ]
            ];
        }


        public function addNavigationBulk($menu, $navigations) {
            foreach($navigations as $key => $row) {
                $this->addNavigation($menu, $row[0], $row[1], $row[2] ?? []);
            }
        }

        public function addNavigation($menu, $label, $url, $attributes = []) {
            if(!isset($this->navs[$menu])) {
                $this->navs[$menu] = [];
            }
            $this->navs[$menu][]= $this->setNav($menu, $label, $url, $attributes);
        }

        public function setNav($menu, $label, $url, $attributes = []) {
            return [
                'label' => $label,
                'url'   => $url,
                'attributes' => $attributes,
                'menu'  => $menu
            ];
        }
    }