# Changelog

## 2.0.2 — 2026-06-18

### Corrección crítica — codificación UTF-8

- Todos los archivos de texto del módulo en **UTF-8 sin BOM** (obligatorio en Magento 2).
- Corregidos `i18n/es_CO.csv` y archivos PHP con caracteres en Latin-1 que provocaban:
  - error 500 al cachear traducciones (`Malformed UTF-8 characters`);
  - ausencia de `js-translation.json` en `es_CO` tras `setup:static-content:deploy`.
- Documentación restaurada a UTF-8.
- Añadidos `.editorconfig` y `dev/verify-utf8.sh` para validar antes de release.

```bash
bash dev/verify-utf8.sh
composer require wompi/magento-payment:^2.0.2
bin/magento setup:static-content:deploy -f es_CO --area frontend --theme Magento/luma
bin/magento cache:flush
```

## 2.0.1 — 2026-05-22

### Cambio de nombre (estándar Magento)

- Paquete Composer: `wompi/magento-gateway-wompi` → **`wompi/magento-payment`**
- Repositorio: `magento-gateway-wompi` → **`magento-payment`**
- Alineado con convención Adobe/Magento (`magento/module-payment`, módulo `Wompi_Payment`)

```bash
composer require wompi/magento-payment:^2.0
```

## 2.0.0 — 2026-05-22

Primera release pública (publicada inicialmente como `wompi/magento-gateway-wompi`).

### Incluye

- Módulo `Wompi_Payment` con Web Checkout (planes Agregador y Gateway)
- Llaves sandbox y producción simultáneas + **Modo de prueba**
- Webhook con firma y verificación API; callback de respaldo
- Status **Pagado** (`wompi_paid`) tras pago aprobado
- Captura offsite sin falso positivo antifraude
- Parches de migración desde configuración legacy en BD
- Documentación en `docs/`

### Vendor

- [Wompi](https://wompi.co) — [ayuda@wompi.co](mailto:ayuda@wompi.co)
