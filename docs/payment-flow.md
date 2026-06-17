# Flujo de pago

<p align="left">
  <img src="assets/wompi_logo_checkout.png" alt="Wompi" width="200" />
</p>

## Diagrama

```mermaid
sequenceDiagram
    participant Buyer as Cliente
    participant Magento as Magento
    participant Wompi as checkout.wompi.co
    participant API as API Wompi

    Buyer->>Magento: Place order (wompi_payment)
    Magento->>Magento: Estado pending_payment
    Magento->>Buyer: Redirect wompi/checkout/start
    Buyer->>Wompi: Web Checkout (GET con firma)
    Wompi->>API: Procesa pago
    Wompi-->>Magento: POST webhook transaction.updated
    Magento->>API: Verifica transacción
    Magento->>Magento: Estado Pagado (wompi_paid)
    Wompi->>Buyer: Redirect callback ?id=
    Buyer->>Magento: GET wompi/payment/callback
    Magento->>Buyer: checkout/onepage/success
```

## Paso a paso

### 1. Checkout Magento

El cliente elige **Wompi** y confirma el pedido. El método `wompi_payment` inicializa el pedido en `pending_payment`.

### 2. Redirect a Wompi

Knockout ejecuta `afterPlaceOrder` → `wompi/checkout/start`.

El controlador construye el payload (`CheckoutFlowResolver`) y envía un formulario GET a `https://checkout.wompi.co/p/`.

Campos principales:

| Campo | Origen |
|-------|--------|
| `public-key` | Config (entorno activo) |
| `reference` | `increment_id` del pedido |
| `amount-in-cents` | Total en COP (centavos) |
| `signature:integrity` | HMAC con integrity secret |
| `redirect-url` | `wompi/payment/callback` del store |

### 3. Webhook (fuente de verdad)

Wompi envía `POST` a `wompi/payment/webhook`. Ver [webhook.md](webhook.md).

### 4. Callback del navegador

```
GET /{store}/wompi/payment/callback?id={transaction_id}
```

Consulta API Wompi y actualiza el pedido si aún no está pagado (idempotente).

## Rutas

| Ruta | Controlador | Método |
|------|-------------|--------|
| `wompi/checkout/start` | `Checkout\Start` | GET |
| `wompi/payment/callback` | `Payment\Callback` | GET |
| `wompi/payment/webhook` | `Payment\Webhook` | POST |
| `wompi/payment/redirect` | `Payment\Redirect` | GET (alias) |

Front name: `wompi` (`etc/frontend/routes.xml`).

## Siguiente paso

[order-states.md](order-states.md) — significado de estados tras el pago.
