<?php

return [

    /*
    |--------------------------------------------------------------------------
    | LDAP / Active Directory Connection
    |--------------------------------------------------------------------------
    |
    | Fill these in with your company's actual LDAP / Active Directory
    | server details in the .env file. Nothing here will work until
    | LDAP_HOST (and either LDAP_DOMAIN or LDAP_DN_FORMAT) are set.
    |
    */

    'host' => env('LDAP_HOST'),

    'port' => env('LDAP_PORT', 389),

    'timeout' => env('LDAP_TIMEOUT', 5),

    'use_tls' => env('LDAP_USE_TLS', false),

    /*
    | Active Directory style bind, e.g. "jdoe@corp.example.com".
    | Set this if your organisation uses Active Directory.
    */
    'domain' => env('LDAP_DOMAIN'),

    /*
    | Classic LDAP DN template, used only if 'domain' above is empty.
    | %s is replaced with the submitted username.
    | Example: "uid=%s,ou=people,dc=example,dc=com"
    */
    'dn_format' => env('LDAP_DN_FORMAT'),

    'base_dn' => env('LDAP_BASE_DN'),

];
