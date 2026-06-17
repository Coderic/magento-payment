# Changelog

## 2.0.6 — 2026-06-18

### Parche verificado en staging (coderic.cloud)

Primera release **validada en ensayo** antes de publicar. Consolida correcciones de 2.0.2–2.0.5:

- UTF-8 sin BOM en todo el módulo (`dev/verify-utf8.sh`)
- Status `wompi_paid` («Pagado») registrado en BD (`InstallWompiOrderStatus`)
- Admin Edit Order muestra Wompi (`CreatePlugin`, `canUseInternal`)
- Flujo: `pending_payment` → `processing` / `wompi_paid` tras webhook

```bash
composer require wompi/magento-payment:^2.0.6
bin/magento module:enable Wompi_Payment
bin/magento setup:upgrade
bin/magento setup:static-content:deploy -f es_CO --area frontend --theme Magento/luma
bin/magento cache:flush
```

## 2.0.5 — 2026-06-18

- `canUseInternal = true` para Admin Edit Order (métodos offsite en Magento admin).

## 2.0.4 — 2026-06-18

- Plugin `CreatePlugin`: copia `wompi_payment` al quote en Edit Order.

## 2.0.3 — 2026-06-18

- Parche `InstallWompiOrderStatus` para `wompi_paid` en `sales_order_status*`.

## 2.0.2 — 2026-06-18

- Codificación UTF-8 sin BOM en todo el módulo.

## 2.0.1 — 2026-05-22

- Paquete renombrado a `wompi/magento-payment`.

## 2.0.0 — 2026-05-22

- Primera release pública Web Checkout Wompi Colombia.

### Vendor

- [Wompi](https://wompi.co) — [ayuda@wompi.co](mailto:ayuda@wompi.co)
