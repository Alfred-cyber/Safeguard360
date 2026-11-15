<?php
// config/navigation.php

// Simple navigation array by role
$navigation_by_role = [
    'superadmin' => [
        'Dashboard' => [
            ['name' => 'Main Dashboard', 'url' => 'dashboard.php', 'icon' => 'ti ti-layout-dashboard']
        ],
        'Users' => [
            ['name' => 'All Users', 'url' => 'users.php', 'icon' => 'ti ti-users'],
            ['name' => 'Add User', 'url' => 'add-user.php', 'icon' => 'ti ti-user-plus']
        ],
        'Clubs' => [
            ['name' => 'All Clubs', 'url' => 'clubs.php', 'icon' => 'ti ti-building'],
            ['name' => 'Club Compliance', 'url' => 'club-compliance.php', 'icon' => 'ti ti-checklist']
        ],
        'Training' => [
            ['name' => 'Courses', 'url' => 'courses.php', 'icon' => 'ti ti-school'],
            ['name' => 'Certificates', 'url' => 'certificates.php', 'icon' => 'ti ti-certificate']
        ],
        'Incidents' => [
            ['name' => 'All Reports', 'url' => 'incidents.php', 'icon' => 'ti ti-alert-triangle'],
            ['name' => 'Report Incident', 'url' => 'report-incident.php', 'icon' => 'ti ti-report']
        ],
        'System' => [
            ['name' => 'Settings', 'url' => 'settings.php', 'icon' => 'ti ti-settings']
        ]
    ],
    
    'club_admin' => [
        'Dashboard' => [
            ['name' => 'Club Dashboard', 'url' => 'dashboard.php', 'icon' => 'ti ti-layout-dashboard']
        ],
        'Members' => [
            ['name' => 'Club Members', 'url' => 'members.php', 'icon' => 'ti ti-users'],
            ['name' => 'Add Member', 'url' => 'add-member.php', 'icon' => 'ti ti-user-plus']
        ],
        'Training' => [
            ['name' => 'Required Courses', 'url' => 'courses.php', 'icon' => 'ti ti-school'],
            ['name' => 'Progress', 'url' => 'progress.php', 'icon' => 'ti ti-progress']
        ],
        'Compliance' => [
            ['name' => 'Check Status', 'url' => 'compliance.php', 'icon' => 'ti ti-checklist']
        ],
        'Incidents' => [
            ['name' => 'Report Incident', 'url' => 'report-incident.php', 'icon' => 'ti ti-report']
        ]
    ],
    
    'coach' => [
        'Dashboard' => [
            ['name' => 'My Dashboard', 'url' => 'dashboard.php', 'icon' => 'ti ti-layout-dashboard']
        ],
        'Training' => [
            ['name' => 'My Courses', 'url' => 'my-courses.php', 'icon' => 'ti ti-school'],
            ['name' => 'My Certificate', 'url' => 'my-certificate.php', 'icon' => 'ti ti-certificate']
        ],
        'Team' => [
            ['name' => 'My Team', 'url' => 'my-team.php', 'icon' => 'ti ti-users']
        ],
        'Incidents' => [
            ['name' => 'Report Incident', 'url' => 'report-incident.php', 'icon' => 'ti ti-report']
        ]
    ],
    
    'volunteer' => [
        'Dashboard' => [
            ['name' => 'My Dashboard', 'url' => 'dashboard.php', 'icon' => 'ti ti-layout-dashboard']
        ],
        'Training' => [
            ['name' => 'My Training', 'url' => 'training.php', 'icon' => 'ti ti-school'],
            ['name' => 'My Certificate', 'url' => 'my-certificate.php', 'icon' => 'ti ti-certificate']
        ],
        'Incidents' => [
            ['name' => 'Report Incident', 'url' => 'report-incident.php', 'icon' => 'ti ti-report']
        ]
    ]
];

// Function to get navigation for a role
function getNavigation($role) {
    global $navigation_by_role;
    return $navigation_by_role[$role] ?? $navigation_by_role['volunteer']; // Default to volunteer
}
?>