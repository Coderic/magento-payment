<?php
declare(strict_types=1);

namespace Wompi\Payment\Setup\Patch\Data;

use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\Patch\DataPatchInterface;

class MigrateLegacyPaymentMethod implements DataPatchInterface
{
    private const LEGACY_METHOD_CODE = 'coderic_wompi_co';

    public function __construct(
        private readonly ModuleDataSetupInterface $moduleDataSetup,
    ) {
    }

    public function apply(): void
    {
        $connection = $this->moduleDataSetup->getConnection();
        $paymentTable = $this->moduleDataSetup->getTable('sales_order_payment');

        $connection->update(
            $paymentTable,
            ['method' => 'wompi_payment'],
            ['method = ?' => self::LEGACY_METHOD_CODE]
        );
    }

    public static function getDependencies(): array
    {
        return [MigrateLegacyPaymentConfig::class];
    }

    public function getAliases(): array
    {
        return [
            'Wompi\\Payment\\Setup\\Patch\\Data\\MigrateCodericWompiPaymentMethod',
        ];
    }
}
