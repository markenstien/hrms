<?php   
    $module = [];
    /**
     * COMPANY MODULE
     */
    $module['timesheet'] = [
        'types' => [
            'PAYOUT' => 'PAYOUT',
            'TIMESHEET_APPROVAL' => 'TIMESHEET APPROVAL',
            'OTHER'  => 'OTHERS'
        ]
    ];

    $module['common'] = [
        'importance-status' => ['LOW','MID','HIGH','CRITICAL']
    ];

    $module['user'] = [
        'types' => [
            'Staff',
            'Administrator',
            'Sub-Administrator'
        ]
    ];

    $module['timesheet'] = [
        'sheet_categories' => [
            'REGULAR',
            'OT'
        ],
        'view_type' => [
            'per_user',
            'free_list'
        ]
    ];

    $module['ee_leave'] = [
        'categories' => [
            'Service Incentive Leave',
            'Sick Leave',
            'Vacation Leave',
            'Maternity Leave',
            'Paternity Leave',
            'Special Leave'
        ],
        'status' => [
            'pending',
            'approved',
            'declined',
            'cancelled'
        ],

        'admin-approval-category' => [
            'Approve With Pay',
            'Approve Without Pay',
            'Declined'
        ]
    ];

    $module['recruitment'] = [
        'statusList' => [
            'passed' => 'Passed',
            'failed' => 'Failed',
            'on-processed' => 'Currently Processing'
        ]
    ];

    $module['holidays'] = [
        'workTypeList' => [
            'non_working' => 'Non Working Holiday',
            'working' => 'Working Holiday'
        ],
        'payTypeList' => [
            'paid' => 'Paid Holiday',
            'unpaid' => 'No Work No Pay Holiday'
        ],
    ];

    return $module;