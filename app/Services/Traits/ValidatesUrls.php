<?php

namespace App\Services\Traits;

use Illuminate\Support\Str;

trait ValidatesUrls
{
    /**
     * Check if the given URL is a valid public remote URL
     */
    private function isPublicRemoteUrl(string $url) : bool
    {
        return $this->getPublicRemoteTarget($url) !== null;
    }

    /**
     * Resolve a validated remote URL into a pinned target payload.
     *
     * @return array{url: string, host: string, port: int, curlResolveEntries: array<int, string>}|null
     */
    private function getPublicRemoteTarget(string $url) : ?array
    {
        if (! $this->isHttpOrHttpsUrl($url)) {
            return null;
        }

        $parts  = parse_url($url);
        $host   = $parts['host'] ?? '';
        $scheme = strtolower((string) ($parts['scheme'] ?? ''));
        $port   = $parts['port'] ?? ($scheme === 'https' ? 443 : 80);

        if ($host === '' || $port < 1) {
            return null;
        }

        $normalizedHost = trim($host, '[]');

        if (filter_var($normalizedHost, FILTER_VALIDATE_IP) !== false) {
            if (! $this->isPublicIp($normalizedHost)) {
                return null;
            }

            return [
                'url'                => $url,
                'host'               => $normalizedHost,
                'port'               => $port,
                'curlResolveEntries' => [],
            ];
        }

        if (! $this->IsValidDomain($normalizedHost)) {
            return null;
        }

        $resolvedIps = $this->getExternalHostIps($normalizedHost);

        if ($resolvedIps === []) {
            return null;
        }

        return [
            'url'                => $url,
            'host'               => $normalizedHost,
            'port'               => $port,
            'curlResolveEntries' => array_map(
                fn (string $ip) : string => sprintf('%s:%d:%s', $normalizedHost, $port, $ip),
                $resolvedIps,
            ),
        ];
    }

    /**
     * Check if the given URL uses the HTTP or HTTPS scheme
     */
    private function isHttpOrHttpsUrl(string $url) : bool
    {
        return Str::of($url)->isUrl(['http', 'https']);
    }

    /**
     * Validates whether the domain name is valid and requires hostnames
     * to start with an alphanumeric character and contain only alphanumerics or hyphens
     */
    private function IsValidDomain(string $host) : bool
    {
        return filter_var($host, FILTER_VALIDATE_DOMAIN, FILTER_FLAG_HOSTNAME) !== false;
    }

    /**
     * Check if the given host resolves only to public external IP addresses
     */
    private function isExternalHost(string $normalizedHost) : bool
    {
        return $this->getExternalHostIps($normalizedHost) !== [];
    }

    /**
     * Resolve the host to public external IP addresses.
     *
     * @return array<int, string>
     */
    private function getExternalHostIps(string $normalizedHost) : array
    {
        try {
            $dnsRecords = $this->getDnsRecords($normalizedHost);
        } catch (\Throwable) {
            return [];
        }

        if ($dnsRecords === []) {
            return [];
        }

        $resolvedIps = [];

        foreach ($dnsRecords as $dnsRecord) {
            $ip = $dnsRecord['ip'] ?? $dnsRecord['ipv6'] ?? null;

            if (! is_string($ip) || ! $this->isPublicIp($ip)) {
                return [];
            }

            $resolvedIps[] = $ip;
        }

        return array_values(array_unique($resolvedIps));
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    protected function getDnsRecords(string $host) : array
    {
        return dns_get_record($host, DNS_A | DNS_AAAA) ?: [];
    }

    /**
     * Check if the given ip is a public ip address
     */
    private function isPublicIp(string $ip) : bool
    {
        if (filter_var($ip, FILTER_VALIDATE_IP) === false) {
            return false;
        }

        if (
            $this->isPrivateAdress($ip)
            || $this->isReservedAdress($ip)
            || $this->isCarrierGradeNatAddress($ip)
            || $this->isMulticast($ip)
            || $this->IsIMDS($ip)
            || $this->isBlockedNat64($ip)
        ) {
            return false;
        }

        return true;
    }

    /**
     * Check if the given ip belongs to a private address range
     * IPv4: 0.0.0.0/8, 169.254.0.0/16, 127.0.0.0/8, 240.0.0.0/4
     * IPv6: ::1/128, ::/128, ::FFFF:0:0/96, FE80::/10
     */
    private function isPrivateAdress(string $ip) : bool
    {
        return filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE) === false;
    }

    /**
     * Check if the given ip belongs to a reserved address range
     * IPv4: 10.0.0.0/8, 172.16.0.0/12, 192.168.0.0/16
     * IPv6 addresses starting with FD or FC
     */
    private function isReservedAdress(string $ip) : bool
    {
        return filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_RES_RANGE) === false;
    }

    /**
     * Check if the given ip belongs to the carrier-grade NAT range
     * IPv4: 100.64.0.0/10
     */
    private function isCarrierGradeNatAddress(string $ip) : bool
    {
        if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4) === false) {
            return false;
        }

        $octets = explode('.', $ip);

        if (count($octets) < 2) {
            return false;
        }

        return $octets[0] === '100' && ((int) $octets[1] >= 64 && (int) $octets[1] <= 127);
    }

    /**
     * Check if the given ip belongs to a multicast address range
     * IPv4: 224.0.0.0/4
     * IPv6: ff00::/8
     */
    private function isMulticast(string $ip) : bool
    {
        if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4) !== false) {
            $long = ip2long($ip);

            return $long !== false && (($long & 0xF0000000) === 0xE0000000);
        }

        if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6) !== false) {
            $binary = inet_pton($ip);

            return is_string($binary) && (ord($binary[0]) === 0xFF);
        }

        return false;
    }

    /**
     * Check if the given ip is an IPv6 NAT64 address whose embedded IPv4 maps to
     * a blocked target.
     *
     * NAT64 (RFC 6052 well-known 64:ff9b::/96 and RFC 8215 local-use
     * 64:ff9b:1::/48) lets an IPv6-only host reach an IPv4 target through a
     * translation gateway. FILTER_FLAG_NO_PRIV_RANGE / NO_RES_RANGE do not flag
     * these prefixes, so an attacker-supplied URL resolving (via DNS64) to e.g.
     * 64:ff9b:1::a9fe:a9fe would otherwise pass as "public" and reach the
     * internal 169.254.169.254 metadata endpoint behind a NAT64 gateway. We
     * decode the embedded IPv4 (low 32 bits) and re-apply the same checks.
     */
    private function isBlockedNat64(string $ip) : bool
    {
        $bin = @inet_pton($ip);

        if ($bin === false || strlen($bin) !== 16) {
            return false;
        }

        $wellKnown = inet_pton('64:ff9b::');   // 64:ff9b::/96  (RFC 6052)
        $localUse  = inet_pton('64:ff9b:1::'); // 64:ff9b:1::/48 (RFC 8215)

        $isNat64 = substr($bin, 0, 12) === substr($wellKnown, 0, 12)
            || substr($bin, 0, 6) === substr($localUse, 0, 6);

        if (! $isNat64) {
            return false;
        }

        $embeddedV4 = long2ip(unpack('N', substr($bin, 12, 4))[1]);

        if ($embeddedV4 === false) {
            // Cannot decode the embedded address: fail closed.
            return true;
        }

        // Re-validate the embedded IPv4 against the full public-IP policy.
        return ! $this->isPublicIp($embeddedV4);
    }

    /**
     * Check if the given ip is the cloud metadata endpoint
     */
    private function IsIMDS(string $ip) : bool
    {
        return $ip === '169.254.169.254';
    }
}
