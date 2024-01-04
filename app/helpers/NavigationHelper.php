<?php 

    class NavigationHelper{
        
        public $navs = [];

        public function __construct()
        {
            $this->moduleRestrict();
            // $this->loadNavs();
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

            
            // $this->addNavigationBulk('Main', [
            //     [
            //         'Dashboard',
            //         _route('dashboard:index')
            //     ]
            // ]);


            // $this->addNavigationBulk('Master', [
            //     [
            //         'Position',
            //         _route('position:index')
            //     ],
            //     [
            //         'Department',
            //         _route('department:index')
            //     ],
            //     [
            //         'Schedule',
            //         _route('admin-shift:index')
            //     ],
            //     [
            //         'Employee',
            //         _route('user:index')
            //     ]
            // ]);

            // $this->addNavigationBulk('Underdevelopment', [
            //     [
            //         'Attendance',
            //         _route('attendance:index')
            //     ],
            //     [
            //         'Payroll',
            //         _route('payroll:index')
            //     ],
            //     [
            //         'Deductons',
            //         _route('deduction:index')
            //     ],
            //     [
            //         'Leave Management',
            //         _route('leave:index')
            //     ]
            // ]);
        }

        private function moduleRestrict() {
            $whoIs = whoIs();

            if($whoIs) {
                $userTypeAccess = $this->userModuleAccess();
                $modelGroup = $this->moduleGroup();

                if($userAccess = $userTypeAccess[strtolower($whoIs['type'])]) {
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


        public function userModuleAccess() {
            $access = [
                'admin' => [
                    'dashboard' => '*',
                    'user' => '*',
                    'attendance' => '*',
                    'position' => '*',
                    'department' => '*',
                    'admin-shift' => '*',
                    'deductions' => '*',
                ],

                'super-admin' => [
                    'dashboard' => '*',
                    'user' => 'view',
                    'attendance' => '*',
                    'position' => 'view',
                    'department' => 'view',
                    'admin-shift' => 'view'
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
                    'attendance' => '*'
                ],

                'hr' => [
                    'dashboard' => '*',
                    'leave' => '*',
                    'leave-point' => '*',
                    'attendance' => '*'
                ],

                'regular_employee' => [
                    'dashboard' => '*',
                    'payroll' => '*',
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
                        'position|index|Position|fa-solid fa-file-invoice-dollar',
                        'department|index|Department',
                        'admin-shift|index|Schedule',
                        'user|index|Employee'
                    ]
                ],

                'hr' => [
                    'label' => 'HR',
                    'items' => [
                        'attendance|index|Attendance',
                        'holiday|index|Holiday',
                        'payroll|index|Payroll',
                        'leave|index|Leave',
                        'recruitment|index|Recruitment'
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