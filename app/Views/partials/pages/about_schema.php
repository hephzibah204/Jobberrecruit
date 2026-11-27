
<script type="application/ld+json">

<?= json_encode([

    '@context' => 'https://schema.org',

    '@type'    => 'AboutPage',

    'name'     => 'About Jobber Recruit LTD',

    'description' => 'Redefining recruitment in Nigeria through a massive digital talent ecosystem that connects ambition with opportunity faster than anyone else.',

    'url'      => current_url(),

    'publisher' => [

        '@type'         => 'Organization',

        'name'          => 'Jobber Recruit LTD',

        'alternateName' => 'JobberRecruit',

        'logo'          => [

            '@type' => 'ImageObject',

            'url'   => base_url('images/logo.png'),

        ],

        'foundingDate' => '2023',

        'address'      => [

            '@type'          => 'PostalAddress',

            'addressCountry' => 'NG',

        ],

        'founder' => 'Jobber Recruit Team',

    ],

    'mainEntity' => [

        '@type'         => 'Organization',

        'name'          => 'Jobber Recruit LTD',

        'description'   => 'A digital-first recruitment agency transforming how talent connects with opportunity across Nigeria through social media networks and digital ecosystems.',

        'url'           => base_url(),

        'address'       => [

            '@type'          => 'PostalAddress',

            'addressCountry' => 'NG',

        ],

    ],

], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) ?>

</script>
