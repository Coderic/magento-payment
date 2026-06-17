<?php
declare(strict_types=1);

namespace Magento\Framework\App\Config;

interface ScopeConfigInterface
{
    /**
     * @param string $path
     * @param string $scopeType
     * @param int|string|null $scopeCode
     * @return mixed
     */
    public function getValue($path, $scopeType = ScopeInterface::SCOPE_TYPE_DEFAULT, $scopeCode = null);

    /**
     * @param string $path
     * @param string $scopeType
     * @param int|string|null $scopeCode
     */
    public function isSetFlag($path, $scopeType = ScopeInterface::SCOPE_TYPE_DEFAULT, $scopeCode = null): bool;
}

namespace Magento\Framework\App\Config;

interface ScopeInterface
{
    public const SCOPE_TYPE_DEFAULT = 'default';
    public const SCOPE_STORE = 'stores';
}

namespace Magento\Framework\Encryption;

interface EncryptorInterface
{
    public function decrypt($data);
}

namespace Magento\Store\Model;

interface StoreManagerInterface
{
}

namespace Magento\Store\Model;

interface ScopeInterface
{
    public const SCOPE_STORE = 'stores';
}
