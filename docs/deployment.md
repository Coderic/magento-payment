# Despliegue

Guía probada en **coderic.cloud** (Magento 2.4.9, PHP 8.3, Luma) y entorno **Podman** local.

## Migración desde v1.x

```bash
composer update wompi/magento-gateway-wompi
bin/magento module:disable Coderic_WompiCo 2>/dev/null || true
bin/magento module:enable Wompi_Payment
bin/magento setup:upgrade --no-interaction
bin/magento setup:di:compile
bin/magento cache:flush
```

`setup:upgrade` aplica parches que migran:

- `payment/coderic_wompi_co/*` ? `payment/wompi_payment/*` (mapeo de llaves según `environment` vigente)
- `sales_order_payment.method` de `coderic_wompi_co` ? `wompi_payment`
- `business_model=agregador` por defecto

## Webhook en panel Wompi

Actualizar la URL registrada (sandbox y producción por separado):

| Antes (v1.x) | Después (v2.0) |
|--------------|----------------|
| `{base_url}/wompico/payment/webhook` | `{base_url}/wompi/payment/webhook` |

Ejemplo producción: `https://coderic.cloud/wompi/payment/webhook`

## Desarrollo local con Podman

### Montaje

En `compose.yaml` del proyecto Magento:

```yaml
- ../magento2-module-wompi-co:/var/www/html/app/code/Wompi/Payment:z
```

### Composer desde el host

Ejecute `composer update` **en el host** (no dentro del contenedor), donde los path repositories `../magento2-module-*` existen:

```bash
cd magento2--coderic-cloud
composer update wompi/magento-gateway-wompi -n --ignore-platform-reqs
```

### Symlinks de vendor en el contenedor

Los symlinks de Composer (`vendor/prodaric/*`, `vendor/wompi/*`) apuntan a rutas hermanas del monorepo que **no están montadas** dentro del contenedor. Si `bin/magento` falla con `Failed opening required ... registration.php`, reorientar symlinks a `app/code/`:

```bash
podman compose exec web bash -c '
cd /var/www/html/vendor/prodaric
for pkg in billing:Billing dian:Dian seniat:Seniat sunat:Sunat; do
  mod="${pkg%%:*}"; dir="${pkg##*:}"
  rm -rf "module-${mod}"
  ln -sf "../../app/code/Prodaric/${dir}" "module-${mod}"
done
mkdir -p /var/www/html/vendor/wompi
cd /var/www/html/vendor/wompi
rm -rf magento-gateway-wompi 2>/dev/null || true
ln -sf ../../app/code/Wompi/Payment magento-gateway-wompi
'
```

Luego ejecutar Magento como `root` en el contenedor si `app/etc/config.php` no es escribible por `www-data`:

```bash
podman compose exec web php bin/magento setup:upgrade --no-interaction
```

### Variables `.env` (store `es_co`)

```bash
WOMPI_ENV=sandbox
WOMPI_PUBLIC_KEY=pub_test_...
WOMPI_PRIVATE_KEY=prv_test_...
WOMPI_INTEGRITY_KEY=test_integrity_...
WOMPI_EVENTS_KEY=test_events_...
```

```bash
bash dev/scripts/apply-wompi-es-co-config.sh
```

Sin las cuatro llaves, el método queda **inactivo** en `es_co` (aviso del script).

## VPS (coderic.cloud)

Desde el monorepo Coderic:

```bash
cd magento2--coderic-cloud
bash dev/scripts/deploy-wompi-vps.sh
```

El script:

1. Crea `app/code/Wompi/Payment` en el VPS si no existe
2. Sincroniza el módulo y `composer.json` / `config.php`
3. Deshabilita `Coderic_WompiCo`, habilita `Wompi_Payment`, `setup:upgrade`, `di:compile`
4. Aplica config Wompi, static deploy Luma y verificación de salud

Verificación manual:

```bash
bash dev/scripts/verify-payment-modules-es-co.sh
```

## Checklist post-despliegue

- [ ] `bin/magento module:status Wompi_Payment` ? enabled
- [ ] Admin: llaves sandbox y producción pegadas; **Modo de prueba** correcto
- [ ] Webhook `/wompi/payment/webhook` en panel Wompi (ambos entornos)
- [ ] Pago sandbox: pedido en status **Pagado** (`wompi_paid`), `total_paid` y factura sin comentario `fraud`
- [ ] Log: `Wompi webhook: payment approved` en `system.log`

## Siguiente paso

[configuration.md](configuration.md) — detalle de campos Admin y llaves dual.
