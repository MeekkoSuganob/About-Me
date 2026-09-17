<?php

// Partner tier data

$partnerTiers = [
    [
        'name' => 'Authorized',
        'price' => '0',
        'description' => 'Entry-level access to our reseller pricing and sales resources.',
        'features' => [
            'Reseller Pricing Access',
            'Sales & Marketing Resources',
            'Listing in Partner Directory',
        ],
    ],
    [
        'name' => 'Silver',
        'price' => '4,999',
        'description' => 'Deal registration and priority technical support.',
        'features' => [
            'Everything from Authorized',
            'Deal Registration',
            'Priority Technical Support',
        ],
    ],
    [
        'name' => 'Gold',
        'price' => '12,999',
        'description' => 'Co-marketing funds and dedicated partner manager.',
        'features' => [
            'Everything from Silver',
            'Co-Marketing Funds',
            'Dedicated Partner Manager',
        ],
    ],
    [
        'name' => 'Platinum',
        'price' => '29,999',
        'description' => 'Top-tier margins, joint go-to-market planning, and executive support.',
        'features' => [
            'Everything from Gold',
            'Top-Tier Margins',
            'Executive Support & Joint GTM Planning',
        ],
    ],
];

// do NOT trust the price
function findPartnerTierByName($name) {
    global $partnerTiers;

    foreach ($partnerTiers as $tier) {
        if ($tier['name'] === $name) {
            return $tier;
        }
    }

    return null;
}
?>