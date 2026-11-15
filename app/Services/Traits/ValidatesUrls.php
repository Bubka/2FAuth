<?php

namespace App\Services\Traits;

use Illuminate\Support\Str;

trait ValidatesUrls
{
    /**
     * IPv4 ranges that are not publicly routable.
     *
     * @var list<string>
     */
    private const BLOCKED_IPV4_RANGES = [
        '0.0.0.0/8',        // "This" network
        '10.0.0.0/8',       // Private-use
        '100.64.0.0/10',    // Carrier-grade NAT
        '127.0.0.0/8',      // Loopback
        '168.63.129.16/32', // Azure platform endpoint
        '169.254.0.0/16',   // Link-local, includes the 169.254.169.254 metadata endpoint
        '172.16.0.0/12',    // Private-use
        '192.0.0.0/24',     // IETF protocol assignments
        '192.0.2.0/24',     // Documentation
        '192.88.99.0/24',   // Deprecated 6to4 relay anycast
        '192.168.0.0/16',   // Private-use
        '198.18.0.0/15',    // Benchmarking
        '198.51.100.0/24',  // Documentation
        '203.0.113.0/24',   // Documentation
        '224.0.0.0/4',      // Multicast
        '240.0.0.0/4',      // Reserved, includes the broadcast address
    ];

    /**
     * IPv6 ranges that are not publicly routable.
     *
     * @var list<string>
     */
    private const BLOCKED_IPV6_RANGES = [
        '::/128',           // Unspecified
        '::1/128',          // Loopback
        '::ffff:0:0:0/96',  // IPv4-translated (SIIT)
        '100::/64',         // Discard-only
        '2001:2::/48',      // Benchmarking
        '2001:10::/28',     // Deprecated ORCHID
        '2001:20::/28',     // ORCHIDv2
        '2001:db8::/32',    // Documentation
        '3fff::/20',        // Documentation
        '5f00::/16',        // Segment Routing SIDs
        'fc00::/7',         // Unique local
        'fe80::/10',        // Link-local
        'fec0::/10',        // Deprecated site-local
        'ff00::/8',         // Multicast
    ];

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
            $this->isInBlockedRange($ip)
            || $this->isBlockedNat64($ip)
            || $this->isBlockedEmbeddedIpv4($ip)
            || $this->isBlockedTunnelAddress($ip)
        ) {
            return false;
        }

        return true;
    }

    /**
     * Check if the given ip belongs to a range that is not publicly routable
     * (IANA special-purpose registries, multicast and cloud metadata endpoints).
     *
     * Ranges embedding an IPv4 (IPv4-mapped, NAT64, 6to4, Teredo) are not listed
     * here: they are decoded and re-validated by the dedicated checks below.
     */
    private function isInBlockedRange(string $ip) : bool
    {
        $binary = inet_pton($ip);

        if ($binary === false) {
            return true;
        }

        $ranges = strlen($binary) === 4 ? self::BLOCKED_IPV4_RANGES : self::BLOCKED_IPV6_RANGES;

        foreach ($ranges as $cidr) {
            if ($this->isInCidr($binary, $cidr)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Check if a packed ip address belongs to the given CIDR range
     */
    private function isInCidr(string $binaryIp, string $cidr) : bool
    {
        [$subnet, $prefixLength] = explode('/', $cidr);

        $binarySubnet = inet_pton($subnet);

        if ($binarySubnet === false || strlen($binarySubnet) !== strlen($binaryIp)) {
            return false;
        }

        $fullBytes     = intdiv((int) $prefixLength, 8);
        $remainingBits = (int) $prefixLength % 8;

        if (substr($binaryIp, 0, $fullBytes) !== substr($binarySubnet, 0, $fullBytes)) {
            return false;
        }

        if ($remainingBits === 0) {
            return true;
        }

        $mask = (0xFF << (8 - $remainingBits)) & 0xFF;

        return (ord($binaryIp[$fullBytes]) & $mask) === (ord($binarySubnet[$fullBytes]) & $mask);
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
     * Check if the given ip is an IPv6 address embedding an IPv4 address in its
     * low 32 bits that is not public: IPv4-mapped (::ffff:0:0/96) or the
     * deprecated IPv4-compatible (::/96) forms.
     *
     * PHP's FILTER_FLAG_NO_PRIV_RANGE / NO_RES_RANGE do not unwrap these, so
     * e.g. ::ffff:169.254.169.254 would otherwise pass as "public".
     */
    private function isBlockedEmbeddedIpv4(string $ip) : bool
    {
        $bin = @inet_pton($ip);

        if ($bin === false || strlen($bin) !== 16) {
            return false;
        }

        $isMapped     = substr($bin, 0, 12) === str_repeat("\0", 10) . "\xff\xff";
        $isCompatible = substr($bin, 0, 12) === str_repeat("\0", 12);

        if (! $isMapped && ! $isCompatible) {
            return false;
        }

        $embeddedV4 = long2ip(unpack('N', substr($bin, 12, 4))[1]);

        // Fail closed if the embedded address cannot be decoded.
        return $embeddedV4 === false || ! $this->isPublicIp($embeddedV4);
    }

    /**
     * Check if the given ip is a 6to4 or Teredo IPv6 address whose embedded IPv4
     * is not public.
     *
     * 6to4 (2002::/16, RFC 3056) carries the IPv4 in bits 16-48.
     * Teredo (2001::/32, RFC 4380) carries the client IPv4 in the low 32 bits,
     * bit-inverted.
     */
    private function isBlockedTunnelAddress(string $ip) : bool
    {
        $bin = @inet_pton($ip);

        if ($bin === false || strlen($bin) !== 16) {
            return false;
        }

        if (substr($bin, 0, 2) === "\x20\x02") {
            $embeddedV4 = inet_ntop(substr($bin, 2, 4));
        } elseif (substr($bin, 0, 4) === "\x20\x01\x00\x00") {
            $embeddedV4 = inet_ntop(~substr($bin, 12, 4));
        } else {
            return false;
        }

        // Fail closed if the embedded address cannot be decoded.
        return $embeddedV4 === false || ! $this->isPublicIp($embeddedV4);
    }
}
