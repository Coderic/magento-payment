# Changelog

## 2.0.1 — 2026-05-22

### Documentación

- Nuevo [deployment.md](deployment.md): migración, Podman, symlinks vendor, VPS y checklist
- [installation.md](installation.md): enlaces a despliegue y repositorio GitHub
- Despliegue verificado en coderic.cloud (Wompi_Payment activo, webhook `/wompi/payment/webhook`)

## 2.0.0 — 2026-05-22

### Breaking

- Rename módulo `Coderic_WompiCo` ? `Wompi_Payment`
- Paquete Composer `coderic/module-wompi-co` ? `wompi/magento-gateway-wompi`
- Método de pago `coderic_wompi_co` ? `wompi_payment`
- Rutas frontend `wompico/*` ? `wompi/*`

### Añadido

- Selector **Plan Wompi** (Agregador / Gateway) en Admin
- Llaves **sandbox y producción simultáneas** + toggle **Modo de prueba**
- Status personalizado **`wompi_paid`** (etiqueta «Pagado») en state `processing`
- `CheckoutFlowInterface` con estrategias Agregador y Gateway
- Resolución de `storeId` desde `reference` en webhook
- Log `info` en webhooks exitosos
- Parches de migración desde `coderic_wompi_co`
- Documentación en `docs/`

### Corregido

- Falso positivo antifraude en captura (`registerCaptureNotification` con `skipFraudDetection`)
- `StoreContext` cuando `store_view_code` está vacío

### Maintainer

- Coderic (`coderic@coderic.org`)

## 1.x (coderic/module-wompi-co)

- Web Checkout Plan Agregador para `es_co`
- Integración inicial Coderic cloud
