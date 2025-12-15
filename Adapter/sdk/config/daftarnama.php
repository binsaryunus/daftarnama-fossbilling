<?php
////// Modules may define default content for package welcome emails here.  Define both a text version and html
////// version for each language you wish to include.  For information on writing email templates see the docs
////// at https://docs.blesta.com/display/user/Customizing+Emails
////

// Transfer fields
Configure::set('Daftarnama.transfer_fields', [
    'domain' => [
        'label' => Language::_('Daftarnama.transfer.domain', true),
        'type' => 'text'
    ],
    'auth_info' => [
        'label' => Language::_('Daftarnama.transfer.auth_info', true),
        'type' => 'text'
    ]
]);

// Domain fields
Configure::set('Daftarnama.domain_fields', [
    'domain' => [
        'label' => Language::_('Daftarnama.domain.domain', true),
        'type' => 'text'
    ],
]);

// Nameserver fields
Configure::set('Daftarnama.nameserver_fields', [
    'nameserver_list[0][name]' => [
        'label' => Language::_('Daftarnama.nameserver.ns1', true),
        'type' => 'text'
    ],
    'nameserver_list[1][name]' => [
        'label' => Language::_('Daftarnama.nameserver.ns2', true),
        'type' => 'text'
    ],
    'nameserver_list[2][name]' => [
        'label' => Language::_('Daftarnama.nameserver.ns3', true),
        'type' => 'text'
    ],
    'nameserver_list[3][name]' => [
        'label' => Language::_('Daftarnama.nameserver.ns4', true),
        'type' => 'text'
    ],
]);

// Whois fields
Configure::set('Daftarnama.whois_fields', [
    'contact_set[owner][first_name]' => [
        'label' => Language::_('Daftarnama.whois.owner.first_name', true),
        'type' => 'text'
    ],
    'contact_set[owner][last_name]' => [
        'label' => Language::_('Daftarnama.whois.owner.last_name', true),
        'type' => 'text'
    ],
    'contact_set[owner][org_name]' => [
        'label' => Language::_('Daftarnama.whois.owner.org_name', true),
        'type' => 'text'
    ],
    'contact_set[owner][address1]' => [
        'label' => Language::_('Daftarnama.whois.owner.address1', true),
        'type' => 'text'
    ],
    'contact_set[owner][address2]' => [
        'label' => Language::_('Daftarnama.whois.owner.address2', true),
        'type' => 'text'
    ],
    'contact_set[owner][city]' => [
        'label' => Language::_('Daftarnama.whois.owner.city', true),
        'type' => 'text'
    ],
    'contact_set[owner][state]' => [
        'label' => Language::_('Daftarnama.whois.owner.state', true),
        'type' => 'text'
    ],
    'contact_set[owner][zip]' => [
        'label' => Language::_('Daftarnama.whois.owner.postal_code', true),
        'type' => 'text'
    ],
    'contact_set[owner][country]' => [
        'label' => Language::_('Daftarnama.whois.owner.country', true),
        'type' => 'text'
    ],
    'contact_set[owner][phone]' => [
        'label' => Language::_('Daftarnama.whois.owner.phone', true),
        'type' => 'text'
    ],
    'contact_set[owner][email]' => [
        'label' => Language::_('Daftarnama.whois.owner.email', true),
        'type' => 'text'
    ],
    'contact_set[tech][first_name]' => [
        'label' => Language::_('Daftarnama.whois.tech.first_name', true),
        'type' => 'text'
    ],
    'contact_set[tech][last_name]' => [
        'label' => Language::_('Daftarnama.whois.tech.last_name', true),
        'type' => 'text'
    ],
    'contact_set[tech][org_name]' => [
        'label' => Language::_('Daftarnama.whois.tech.org_name', true),
        'type' => 'text'
    ],
    'contact_set[tech][address1]' => [
        'label' => Language::_('Daftarnama.whois.tech.address1', true),
        'type' => 'text'
    ],
    'contact_set[tech][address2]' => [
        'label' => Language::_('Daftarnama.whois.tech.address2', true),
        'type' => 'text'
    ],
    'contact_set[tech][city]' => [
        'label' => Language::_('Daftarnama.whois.tech.city', true),
        'type' => 'text'
    ],
    'contact_set[tech][state]' => [
        'label' => Language::_('Daftarnama.whois.tech.state', true),
        'type' => 'text'
    ],
    'contact_set[tech][zip]' => [
        'label' => Language::_('Daftarnama.whois.tech.postal_code', true),
        'type' => 'text'
    ],
    'contact_set[tech][country]' => [
        'label' => Language::_('Daftarnama.whois.tech.country', true),
        'type' => 'text'
    ],
    'contact_set[tech][phone]' => [
        'label' => Language::_('Daftarnama.whois.tech.phone', true),
        'type' => 'text'
    ],
    'contact_set[tech][email]' => [
        'label' => Language::_('Daftarnama.whois.tech.email', true),
        'type' => 'text'
    ],
    'contact_set[admin][first_name]' => [
        'label' => Language::_('Daftarnama.whois.admin.first_name', true),
        'type' => 'text'
    ],
    'contact_set[admin][last_name]' => [
        'label' => Language::_('Daftarnama.whois.admin.last_name', true),
        'type' => 'text'
    ],
    'contact_set[admin][org_name]' => [
        'label' => Language::_('Daftarnama.whois.admin.org_name', true),
        'type' => 'text'
    ],
    'contact_set[admin][address1]' => [
        'label' => Language::_('Daftarnama.whois.admin.address1', true),
        'type' => 'text'
    ],
    'contact_set[admin][address2]' => [
        'label' => Language::_('Daftarnama.whois.admin.address2', true),
        'type' => 'text'
    ],
    'contact_set[admin][city]' => [
        'label' => Language::_('Daftarnama.whois.admin.city', true),
        'type' => 'text'
    ],
    'contact_set[admin][state]' => [
        'label' => Language::_('Daftarnama.whois.admin.state', true),
        'type' => 'text'
    ],
    'contact_set[admin][zip]' => [
        'label' => Language::_('Daftarnama.whois.admin.postal_code', true),
        'type' => 'text'
    ],
    'contact_set[admin][country]' => [
        'label' => Language::_('Daftarnama.whois.admin.country', true),
        'type' => 'text'
    ],
    'contact_set[admin][phone]' => [
        'label' => Language::_('Daftarnama.whois.admin.phone', true),
        'type' => 'text'
    ],
    'contact_set[admin][email]' => [
        'label' => Language::_('Daftarnama.whois.admin.email', true),
        'type' => 'text'
    ],
    'contact_set[billing][first_name]' => [
        'label' => Language::_('Daftarnama.whois.billing.first_name', true),
        'type' => 'text'
    ],
    'contact_set[billing][last_name]' => [
        'label' => Language::_('Daftarnama.whois.billing.last_name', true),
        'type' => 'text'
    ],
    'contact_set[billing][org_name]' => [
        'label' => Language::_('Daftarnama.whois.billing.org_name', true),
        'type' => 'text'
    ],
    'contact_set[billing][address1]' => [
        'label' => Language::_('Daftarnama.whois.billing.address1', true),
        'type' => 'text'
    ],
    'contact_set[billing][address2]' => [
        'label' => Language::_('Daftarnama.whois.billing.address2', true),
        'type' => 'text'
    ],
    'contact_set[billing][city]' => [
        'label' => Language::_('Daftarnama.whois.billing.city', true),
        'type' => 'text'
    ],
    'contact_set[billing][state]' => [
        'label' => Language::_('Daftarnama.whois.billing.state', true),
        'type' => 'text'
    ],
    'contact_set[billing][zip]' => [
        'label' => Language::_('Daftarnama.whois.billing.postal_code', true),
        'type' => 'text'
    ],
    'contact_set[billing][country]' => [
        'label' => Language::_('Daftarnama.whois.billing.country', true),
        'type' => 'text'
    ],
    'contact_set[billing][phone]' => [
        'label' => Language::_('Daftarnama.whois.billing.phone', true),
        'type' => 'text'
    ],
    'contact_set[billing][email]' => [
        'label' => Language::_('Daftarnama.whois.billing.email', true),
        'type' => 'text'
    ]
]);

// Welcome Email templates
Configure::set('Daftarnama.email_templates', [
    'en_us' => [
        'lang' => 'en_us',
        'text' => 'Your new domain is being processed and will be registered soon!

Domain: {service.domain}

Thank you for your business!',
        'html' => '<p>Your new domain is being processed and will be registered soon!</p>
<p>Domain: {service.domain}</p>
<p>Thank you for your business!</p>'
    ]
]);