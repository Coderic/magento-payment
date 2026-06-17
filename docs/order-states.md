# Estados de pedido

Referencia: [Order status | Adobe Commerce](https://experienceleague.adobe.com/en/docs/commerce-admin/stores-sales/order-management/orders/order-status)

## Flujo Wompi (offsite)

| Fase | State Magento | Status | Etiqueta visible |
|------|---------------|--------|------------------|
| Pedido creado, cliente redirigido a Wompi | `pending_payment` | `pending_payment` | Pendiente de pago |
| Pago aprobado (webhook o callback) | `processing` | `wompi_paid` | **Pagado** |
| Servicio entregado / pedido cerrado | `complete` | `complete` | Completado |
| Pago rechazado o abandonado | `canceled` | `canceled` | Cancelado |

En Magento, **state** define el flujo programático; **status** es la etiqueta para Admin y clientes. Tras confirmar el pago, Adobe asigna el state **processing** (pago recibido, pendiente de preparar/enviar). Este módulo usa el status personalizado **`wompi_paid`** con etiqueta **Pagado** en lugar del genérico «Procesando».

## Estados Wompi (API)

| Status Wompi | Acción del módulo |
|--------------|-------------------|
| `APPROVED` | Marca pedido pagado + captura |
| `DECLINED`, `ERROR`, `VOIDED` | Cancela el pedido |

## Webhook vs callback

| Mecanismo | Rol |
|-----------|-----|
| **Webhook** | Fuente de verdad |
| **Callback** | Retorno del navegador; respaldo UX |

Ambos caminos son idempotentes.

## Captura y antifraude

`registerCaptureNotification($amount, true)` evita falsos positivos de fraude en pagos offsite ya verificados por API Wompi.
