<?php
declare(strict_types=1);

namespace Wompi\Payment\Test\Unit\Model;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\Encryption\EncryptorInterface;
use Magento\Store\Model\ScopeInterface;
use Magento\Store\Model\StoreManagerInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Wompi\Payment\Model\Config;

class ConfigTest extends TestCase
{
    private ScopeConfigInterface&MockObject $scopeConfig;
    private StoreManagerInterface&MockObject $storeManager;
    private EncryptorInterface&MockObject $encryptor;
    private Config $config;

    protected function setUp(): void
    {
        $this->scopeConfig = $this->createMock(ScopeConfigInterface::class);
        $this->storeManager = $this->createMock(StoreManagerInterface::class);
        $this->encryptor = $this->createMock(EncryptorInterface::class);
        $this->config = new Config($this->scopeConfig, $this->storeManager, $this->encryptor);
    }

    public function testGetIntegrityKeyDecryptsEncryptedProductionValue(): void
    {
        $encrypted = '0:3:ciphertext';
        $plain = 'prod_integrity_fEFHnMSRj4yYHL8brxdCl4zF8rzEeITC';

        $this->scopeConfig->method('getValue')->willReturnCallback(
            static function (string $path, string $scope = ScopeInterface::SCOPE_STORE, $storeId = null) use ($encrypted): ?string {
                if ($path === 'payment/wompi_payment/test_mode') {
                    return 'production';
                }
                if ($path === 'payment/wompi_payment/integrity_key_production') {
                    return $encrypted;
                }

                return null;
            }
        );

        $this->encryptor->expects($this->once())
            ->method('decrypt')
            ->with($encrypted)
            ->willReturn($plain);

        $this->assertSame($plain, $this->config->getIntegrityKey(2));
    }

    public function testGetIntegrityKeyReturnsPlainTextWithoutDecrypt(): void
    {
        $plain = 'test_integrity_plain';

        $this->scopeConfig->method('getValue')->willReturnCallback(
            static function (string $path) use ($plain): ?string {
                if ($path === 'payment/wompi_payment/test_mode') {
                    return 'sandbox';
                }
                if ($path === 'payment/wompi_payment/integrity_key_test') {
                    return $plain;
                }

                return null;
            }
        );

        $this->encryptor->expects($this->never())->method('decrypt');

        $this->assertSame($plain, $this->config->getIntegrityKey(2));
    }

    public function testGetPublicKeyIsNotDecrypted(): void
    {
        $publicKey = 'pub_prod_XsWJkGZDTSOWZC52BNYDL9CgJIt1P3Vy';

        $this->scopeConfig->method('getValue')->willReturnCallback(
            static function (string $path) use ($publicKey): ?string {
                if ($path === 'payment/wompi_payment/test_mode') {
                    return 'production';
                }
                if ($path === 'payment/wompi_payment/public_key_production') {
                    return $publicKey;
                }

                return null;
            }
        );

        $this->encryptor->expects($this->never())->method('decrypt');

        $this->assertSame($publicKey, $this->config->getPublicKey(2));
    }
}
