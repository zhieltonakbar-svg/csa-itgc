<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;

class LdapAuthService
{
    /**
     * Attempt to authenticate a username/password pair against
     * the company LDAP / Active Directory server.
     *
     * Configure the connection in config/ldap.php (values come
     * from your .env — see LDAP_* keys). This class deliberately
     * never throws: any LDAP/network problem is treated as a
     * failed login and logged for the Admin to investigate.
     */
    public function attempt(string $username, string $password): bool
    {
        if (trim($username) === '' || trim($password) === '') {
            return false;
        }

        if (!extension_loaded('ldap')) {
            Log::error(
                'LDAP login attempted but the PHP ldap extension is not installed/enabled.'
            );

            return false;
        }

        $host = config('ldap.host');
        $port = config('ldap.port', 389);

        if (!$host) {
            Log::error(
                'LDAP login attempted but LDAP_HOST is not configured in .env.'
            );

            return false;
        }

        $connection = null;

        try {
            $connection = ldap_connect($host, $port);

            if (!$connection) {
                Log::error("LDAP: could not connect to {$host}:{$port}");

                return false;
            }

            ldap_set_option($connection, LDAP_OPT_PROTOCOL_VERSION, 3);
            ldap_set_option($connection, LDAP_OPT_REFERRALS, 0);
            ldap_set_option(
                $connection,
                LDAP_OPT_NETWORK_TIMEOUT,
                (int) config('ldap.timeout', 5)
            );

            if (config('ldap.use_tls')) {
                ldap_start_tls($connection);
            }

            $bindDn = $this->buildBindDn($username);

            $bound = @ldap_bind($connection, $bindDn, $password);

            if (!$bound) {
                Log::warning(
                    "LDAP: bind failed for user '{$username}'."
                );
            }

            return (bool) $bound;

        } catch (\Throwable $e) {

            Log::error(
                'LDAP authentication error: ' . $e->getMessage()
            );

            return false;

        } finally {

            if ($connection) {
                @ldap_unbind($connection);
            }

        }
    }

    /**
     * Build the DN (or Active-Directory-style UPN) used to bind.
     *
     * Two common patterns are supported via config/ldap.php:
     *  - Active Directory:  "{username}@{domain}"  (LDAP_DOMAIN set)
     *  - Classic LDAP DN:   "uid={username},{base_dn}" (LDAP_DN_FORMAT set)
     *
     * Adjust to match your organisation's directory structure.
     */
    protected function buildBindDn(string $username): string
    {
        $domain = config('ldap.domain');

        if ($domain) {
            return "{$username}@{$domain}";
        }

        $dnFormat = config('ldap.dn_format', 'uid=%s,' . config('ldap.base_dn'));

        return sprintf($dnFormat, $username);
    }
}
