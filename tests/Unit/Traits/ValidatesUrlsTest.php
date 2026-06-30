<?php

namespace Tests\Unit\Traits;

use App\Services\Traits\ValidatesUrls;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * ValidatesUrlsTest test class
 */
#[CoversClass(ValidatesUrls::class)]
class ValidatesUrlsTest extends TestCase
{
	#[Test]
	#[DataProvider('publicIpProvider')]
	public function test_isPublicIp_returns_true_for_public_ips(string $ip)
	{
		$validator = new class
		{
			use ValidatesUrls;

			public function check(string $ip) : bool
			{
				return $this->isPublicIp($ip);
			}
		};

		$this->assertTrue($validator->check($ip));
	}

    /**
     * Data provider for public IP addresses
     */
	public static function publicIpProvider() : array
	{
		return [
			'PUBLIC_IPV4' => ['1.1.1.1'],
			'PUBLIC_IPV6' => ['2606:4700:4700::1111'],
		];
	}

	#[Test]
	#[DataProvider('nonPublicIpProvider')]
	public function test_isPublicIp_returns_false_for_non_public_ranges(string $ip)
	{
		$validator = new class
		{
			use ValidatesUrls;

			public function check(string $ip) : bool
			{
				return $this->isPublicIp($ip);
			}
		};

		$this->assertFalse($validator->check($ip));
	}

    /**
     * Data provider for non-public IP addresses
     */
	public static function nonPublicIpProvider() : array
	{
		return [
			'PRIVATE_CLASS_A_10_8' => ['10.0.0.1'],
			'PRIVATE_CLASS_B_172_16_12' => ['172.16.0.1'],
			'PRIVATE_CLASS_C_192_168_16' => ['192.168.1.1'],
			'LOOPBACK_127_8' => ['127.0.0.1'],
			'LINK_LOCAL_169_254_16' => ['169.254.10.10'],
			'UNSPECIFIED_0_8' => ['0.0.0.0'],
			'RESERVED_240_4' => ['240.0.0.1'],
			'BROADCAST_255_255_255_255' => ['255.255.255.255'],
			'UNIQUE_LOCAL_FC00_7' => ['fc00::1'],
			'LINK_LOCAL_FE80_10' => ['fe80::1'],
			'LOOPBACK_V6' => ['::1'],
			'UNSPECIFIED_V6' => ['::'],
			'CARRIER_GRADE_NAT_100_64_10' => ['100.64.0.1'],
			'MULTICAST_IPV4_224_4' => ['224.0.0.1'],
			'MULTICAST_IPV6_FF00_8' => ['ff02::1'],
			'IMDS_IPV4' => ['169.254.169.254'],
		];
	}

	#[Test]
	#[DataProvider('publicRemoteUrlProvider')]
	public function test_isPublicRemoteUrl_returns_expected_result(string $url, bool $expected)
	{
		$validator = new class
		{
			use ValidatesUrls;

			public function checkUrl(string $url) : bool
			{
				return $this->isPublicRemoteUrl($url);
			}
		};

		$this->assertSame($expected, $validator->checkUrl($url));
	}

    /**
     * Data provider for public remote URL tests
     */
	public static function publicRemoteUrlProvider() : array
	{
		return [
			'PUBLIC_IPV4_HTTP' => ['http://1.1.1.1/logo.png', true],
			'PUBLIC_IPV4_HTTPS' => ['https://8.8.8.8/icon.webp', true],
			'PUBLIC_IPV6_HTTP' => ['http://[2606:4700:4700::1111]/icon.svg', true],
			'PUBLIC_IPV6_HTTPS' => ['https://[2001:4860:4860::8888]/icon.png', true],
			'PRIVATE_IPV4_10_8' => ['https://10.0.0.1/image.png', false],
			'PRIVATE_IPV4_172_16_12' => ['https://172.16.0.1/image.png', false],
			'PRIVATE_IPV4_192_168_16' => ['https://192.168.1.1/image.png', false],
			'LOOPBACK_IPV4' => ['https://127.0.0.1/image.png', false],
			'LOOPBACK_IPV4_HEX' => ['https://0x7f.0x0.0x0.0x1/image.png', false],
			'LOOPBACK_IPV4_OCT' => ['https://0177.0.0.01/image.png', false],
			'LOOPBACK_IPV4_INT' => ['https://2130706433/image.png', false],
			'LINK_LOCAL_IPV4' => ['https://169.254.10.10/image.png', false],
			'UNIQUE_LOCAL_IPV6' => ['https://[fc00::1]/image.png', false],
			'LOOPBACK_IPV6' => ['https://[::1]/image.png', false],
			'LINK_LOCAL_IPV6' => ['https://[fe80::1]/image.png', false],
			'CARRIER_GRADE_NAT_IPV4' => ['https://100.64.0.1/image.png', false],
			'MULTICAST_IPV4' => ['https://224.0.0.1/image.png', false],
			'MULTICAST_IPV6' => ['https://[ff02::1]/image.png', false],
			'IMDS_ENDPOINT' => ['https://169.254.169.254/latest/meta-data', false],
			'INVALID_SCHEME' => ['ftp://1.1.1.1/logo.png', false],
			'INVALID_URL' => ['not-a-url', false],
			'LOCALHOST' => ['http://localhost/logo.png', false],
			'LOCALHOST_ENCODED' => ['http://%6c%6f%63%61%6c%68%6f%73%74/logo.png', false],
		];
	}

	#[Test]
	public function test_getPublicRemoteTarget_returns_expected_payload_for_domain() : void
	{
		$validator = new class
		{
			use ValidatesUrls;

			public function resolveTarget(string $url) : ?array
			{
				return $this->getPublicRemoteTarget($url);
			}

			protected function getDnsRecords(string $host) : array
			{
				return [
					['ip' => '1.1.1.1'],
					['ipv6' => '2606:4700:4700::1111'],
				];
			}
		};

		$target = $validator->resolveTarget('https://example.com/logo.png');

		$this->assertNotNull($target);
		$this->assertSame('https://example.com/logo.png', $target['url']);
		$this->assertSame('example.com', $target['host']);
		$this->assertSame(443, $target['port']);
		$this->assertSame([
			'example.com:443:1.1.1.1',
			'example.com:443:2606:4700:4700::1111',
		], $target['curlResolveEntries']);
	}

	#[Test]
	public function test_getPublicRemoteTarget_returns_empty_resolve_entries_for_literal_ip() : void
	{
		$validator = new class
		{
			use ValidatesUrls;

			public function resolveTarget(string $url) : ?array
			{
				return $this->getPublicRemoteTarget($url);
			}
		};

		$target = $validator->resolveTarget('https://8.8.8.8/logo.png');

		$this->assertNotNull($target);
		$this->assertSame([], $target['curlResolveEntries']);
	}

	#[Test]
	public function test_getPublicRemoteTarget_returns_null_when_dns_is_not_public() : void
	{
		$validator = new class
		{
			use ValidatesUrls;

			public function resolveTarget(string $url) : ?array
			{
				return $this->getPublicRemoteTarget($url);
			}

			protected function getDnsRecords(string $host) : array
			{
				return [
					['ip' => '127.0.0.1'],
				];
			}
		};

		$this->assertNull($validator->resolveTarget('https://example.com/logo.png'));
	}

}
