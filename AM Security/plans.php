<?php
// ── Shared plan data ─────────────────────────────────────────────────────
// This is the single source of truth for plan names, prices, and features.
// services.php and checkout.php both include this file, so a price can
// never be trusted from the browser -- checkout always looks it up here
// by plan name instead of trusting anything submitted in a form.

$consumerPlans = [
    [
        'name' => 'AM Security Basic',
        'devices' => '1 Account, 5 Devices',
        'description' => "If you're looking for a basic device protection.",
        'original_price' => '1,499',
        'price' => '999',
        'save' => '33%',
        'previous_tier' => null,
        'features' => [
            'Real-Time Threat Detection',
            'Basic Firewall Protection',
            'Automatic Virus Definition Updates',
        ],
    ],
    [
        'name' => 'AM Security Standard',
        'devices' => '1 Account, 5 Devices',
        'description' => "If you're looking for essential device protection.",
        'original_price' => '2,499',
        'price' => '1,499',
        'save' => '40%',
        'previous_tier' => 'Essential',
        'features' => [
            'Link to your mobile devices',
            'Ransomware Protection',
            'Password Manager',
        ],
    ],
    [
        'name' => 'AM Security Premium',
        'devices' => '1 Account, 5 Devices',
        'description' => "If you're looking to protect your devices and sensitive data.",
        'original_price' => '3,499',
        'price' => '2,199',
        'save' => '37%',
        'previous_tier' => 'Standard',
        'features' => [
            'Unlimited VPN Traffic',
            'Anti-Tracker',
            'Email Protection for 3 Mail Addresses',
        ],
    ],
    [
        'name' => 'AM Security Ultimate',
        'devices' => '1 Account, 5 Devices',
        'description' => "If you're looking for all-out protection for your devices and identity online.",
        'original_price' => '4,999',
        'price' => '3,299',
        'save' => '34%',
        'previous_tier' => 'Premium',
        'features' => [
            'Identity Theft Insurance',
            'Real-Time Breach Notification',
            'Credit Monitoring',
        ],
    ],
];

$businessPlans = [
    [
        'name' => 'AM Business Starter',
        'devices' => '1 Account, 10 Devices',
        'description' => "Perfect for small teams getting started with device protection.",
        'original_price' => '9,999',
        'price' => '6,499',
        'save' => '35%',
        'previous_tier' => null,
        'features' => [
            'Up to 10 Devices',
            'Centralized Admin Dashboard',
            'Business-Grade Firewall',
        ],
    ],
    [
        'name' => 'AM Business Growth',
        'devices' => '1 Account, 25 Devices',
        'description' => "Perfect for growing teams that need more coverage and control.",
        'original_price' => '17,999',
        'price' => '11,999',
        'save' => '33%',
        'previous_tier' => 'Starter',
        'features' => [
            'Up to 25 Devices',
            'Ransomware Rollback',
            'Employee Usage Reports',
        ],
    ],
    [
        'name' => 'AM Business Professional',
        'devices' => '1 Account, 50 Devices',
        'description' => "Perfect for established businesses with sensitive company data.",
        'original_price' => '29,999',
        'price' => '19,999',
        'save' => '33%',
        'previous_tier' => 'Growth',
        'features' => [
            'Up to 50 Devices',
            'Advanced Threat Intelligence',
            'Priority Email Support',
        ],
    ],
    [
        'name' => 'AM Business Corporate',
        'devices' => '1 Account, 100 Devices',
        'description' => "Perfect for larger businesses needing full-scale protection.",
        'original_price' => '49,999',
        'price' => '34,999',
        'save' => '30%',
        'previous_tier' => 'Professional',
        'features' => [
            'Up to 100 Devices',
            'Dedicated Account Manager',
            '24/7 Phone Support',
        ],
    ],
];

$enterprisePlans = [
    [
        'name' => 'AM Enterprise Core',
        'devices' => 'Unlimited Accounts, 250 Devices',
        'description' => "For organizations needing scalable baseline protection.",
        'original_price' => '129,999',
        'price' => '89,999',
        'save' => '31%',
        'previous_tier' => null,
        'features' => [
            'Up to 250 Devices',
            'Custom Security Policies',
            'SIEM Integration',
        ],
    ],
    [
        'name' => 'AM Enterprise Plus',
        'devices' => 'Unlimited Accounts, 500 Devices',
        'description' => "For organizations with complex, distributed infrastructure.",
        'original_price' => '249,999',
        'price' => '174,999',
        'save' => '30%',
        'previous_tier' => 'Core',
        'features' => [
            'Up to 500 Devices',
            'Advanced Endpoint Detection & Response',
            'Compliance Reporting Suite',
        ],
    ],
    [
        'name' => 'AM Enterprise Complete',
        'devices' => 'Unlimited Accounts, Unlimited Devices',
        'description' => "For large enterprises requiring full-spectrum security.",
        'original_price' => '499,999',
        'price' => '349,999',
        'save' => '30%',
        'previous_tier' => 'Plus',
        'features' => [
            'Unlimited Devices',
            'Dedicated Security Operations Team',
            'Custom Onboarding & Training',
        ],
    ],
];


// Searches all three plan lists for a plan by exact name. Returns the plan
// array (plus which section it belongs to) or null if no match is found.
// This is what checkout.php uses to get the *real* price for an order,
// instead of trusting a price sent from the browser.
function findPlanByName($name) {
    global $consumerPlans, $businessPlans, $enterprisePlans;

    $sections = [
        'Consumer' => $consumerPlans,
        'Business' => $businessPlans,
        'Enterprise' => $enterprisePlans,
    ];

    foreach ($sections as $sectionName => $plans) {
        foreach ($plans as $plan) {
            if ($plan['name'] === $name) {
                $plan['section'] = $sectionName;
                return $plan;
            }
        }
    }

    return null;
}
?>