<?php 

    class NavigationHelper{
        
        public $navs = [];

        public function __construct()
        {
            $this->loadNavs();
        }

        public function getNavsHTML() {
            $navs = $this->navs;
            $retValHTML = '';
            if(!empty($navs)) {
                foreach($navs as $navGroupName => $navGroups) {
                    $retValHTML .= "<div class='sidebar-heading'>{$navGroupName}</div>";
                    foreach($navGroups as $navItem) {
                        $attributes = $navItem['attributes'];
                        $icon = $attributes['icon'] ?? 'fas fa-fw fa-tachometer-alt';
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
            
            $this->addNavigationBulk('Main', [
                [
                    'Dashboard',
                    _route('dashboard:index')
                ]
            ]);


            $this->addNavigationBulk('Master', [
                [
                    'Position',
                    _route('position:index')
                ],
                [
                    'Department',
                    _route('department:index')
                ],
                [
                    'Schedule',
                    _route('admin-shift:index')
                ],
                [
                    'Employee',
                    _route('user:index')
                ]
            ]);

            $this->addNavigationBulk('Underdevelopment', [
                [
                    'Attendance',
                    _route('attendance:index')
                ],
                [
                    'Payroll',
                    _route('payroll:index')
                ],
                [
                    'Deductons',
                    _route('deduction:index')
                ],
                [
                    'Leave Management',
                    _route('leave:index')
                ]
            ]);
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