<?php
declare(strict_types=1);

namespace Wompi\Payment\Setup\Patch\Data;

use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\Patch\DataPatchInterface;
use Magento\Sales\Setup\Patch\Data\InstallOrderStatusesAndInitialSalesConfig;
use Wompi\Payment\Service\OrderPaymentUpdater;

/**
 * Registra el status wompi_paid en sales_order_status* (order_statuses.xml no persiste en BD en M2.4).
 */
class InstallWompiOrderStatus implements DataPatchInterface
{
    public function __construct(
        private readonly ModuleDataSetupInterface $moduleDataSetup,
    ) {
    }

    public function apply(): void
    {
        $connection = $this->moduleDataSetup->getConnection();
        $this->moduleDataSetup->getConnection()->startSetup();

        $statusTable = $this->moduleDataSetup->getTable('sales_order_status');
        $stateTable = $this->moduleDataSetup->getTable('sales_order_status_state');
        $code = OrderPaymentUpdater::STATUS_WOMPI_PAID;

        $exists = (bool) $connection->fetchOne(
            $connection->select()
                ->from($statusTable, ['status'])
                ->where('status = ?', $code)
                ->limit(1)
        );

        if (!$exists) {
            $connection->insert($statusTable, [
                'status' => $code,
                'label' => 'Pagado',
            ]);
        }

        $assigned = (bool) $connection->fetchOne(
            $connection->select()
                ->from($stateTable, ['status'])
                ->where('status = ?', $code)
                ->where('state = ?', 'processing')
                ->limit(1)
        );

        if (!$assigned) {
            $connection->insert($stateTable, [
                'status' => $code,
                'state' => 'processing',
                'is_default' => 0,
                'visible_on_front' => 1,
            ]);
        }

        $this->moduleDataSetup->getConnection()->endSetup();
    }

    public static function getDependencies(): array
    {
        return [InstallOrderStatusesAndInitialSalesConfig::class];
    }

    public function getAliases(): array
    {
        return [];
    }
}
