# bdPay — Mobile Banking Gateway for WHMCS

A manual payment gateway module for [WHMCS](https://www.whmcs.com/) that allows customers to pay invoices via Bangladesh's popular mobile banking services — **bKash**, **Nagad**, and **Rocket**. After payment, customers submit their transaction details for admin verification via WhatsApp or Email.

## Features

- ✅ Supports **bKash**, **Nagad**, and **Rocket** in a single module
- ✅ Clean, minimal popup modal with tabbed gateway selection
- ✅ Step-by-step payment instructions (numbered, left-aligned)
- ✅ One-click **copy** buttons for account number, amount, and invoice reference
- ✅ Displays exact payable amount with currency (e.g., `2500.00 BDT`)
- ✅ Verification via **WhatsApp** or **Email** with pre-filled transaction details
- ✅ Uses WHMCS default **Pay Now** button — no custom styling on the invoice page
- ✅ No external font or icon libraries — zero dependencies
- ✅ Mobile-friendly responsive layout

## Requirements

- WHMCS 7.x or higher
- PHP 7.4+
- SSL (HTTPS) recommended for clipboard API support

## Installation

1. Copy `bdpay.php` into your WHMCS installation at:
   ```
   /modules/gateways/bdpay.php
   ```

2. Log in to your **WHMCS Admin Panel**.
3. Go to **Setup → Payment Gateways → All Payment Gateways**.
4. Find **Mobile Banking (bdPay)** and click **Activate**.
5. Go to **Setup → Payment Gateways → Manage Existing Gateways**.
6. Click **Configure** next to **Mobile Banking (bdPay)** and fill in the required fields.

## Configuration

| Field | Description |
|---|---|
| **bKash Number** | Your bKash merchant/personal number |
| **Nagad Number** | Your Nagad merchant/personal number |
| **Rocket Number** | Your Rocket merchant/personal number |
| **Admin WhatsApp Number** | Full number with country code, e.g. `8801712345678` |
| **Admin Email Address** | Email address to receive verification messages |

> **Note:** You only need to fill in the numbers for the gateways you want to offer. If a number is left blank, that tab will still appear, but the number field will be empty.

## How It Works

### Customer Flow

1. Customer views an invoice and clicks **Pay Now**.
2. A modal popup appears with three tabs: **bKash**, **Nagad**, **Rocket**.
3. Customer selects their preferred gateway and follows the 7-step guide:
   1. Open the app or dial the USSD code
   2. Select **Send Money**
   3. Copy and enter the account number
   4. Copy and enter the exact payable amount
   5. Add the invoice number as the Reference
   6. Confirm with PIN
   7. Tap to pay
4. After payment, the customer enters their **sender account number** and **Transaction ID (TxID)**.
5. They click **Verify via WhatsApp** or **Verify via Email** — a pre-filled message is sent to the admin.

### Admin Flow

1. Admin receives a WhatsApp message or email with:
   - Invoice number
   - Gateway used (bKash / Nagad / Rocket)
   - Amount
   - Sender's account number
   - Transaction ID (TxID)
2. Admin verifies the transaction manually in the respective banking app.
3. Admin marks the invoice as **Paid** in WHMCS.

## File Structure

```text
modules/
└── gateways/
    ├── bdpay/
    │   ├── logo.png
    │   └── whmcs.json
    └── bdpay.php        ← Main gateway module file
```

## Customization

All styling is embedded directly in `bdpay.php` within the `bdpay_link()` function using CSS custom properties (variables). You can adjust colors by editing the `: root` block:

```css
: root {
  --bk: #e2136e;   /* bKash pink */
  --ng: #f97316;   /* Nagad orange */
  --rk: #7c3aed;   /* Rocket violet */
  --tx: #575757;   /* Body text */
  --bd: #e2e8f0;   /* Borders */
  --sl: #f8fafc;   /* Subtle background */
}
```

## Verification Message Format

When the customer clicks **Verify**, the following message is sent:

```
Payment Verification
Invoice: #[ID]
Gateway: [bKash / Nagad / Rocket]
Amount: [amount] [currency]
Sender: [customer's account number]
TxID: [transaction ID]
```

## Limitations

- This is a **manual gateway** — no automated payment confirmation via API.
- Admin must verify each transaction manually and mark invoices as paid.
- The module does not store transaction data in the WHMCS database.

## Changelog

### v1.0.0
- Initial release with bKash, Nagad, and Rocket support
- Tabbed modal UI with step-by-step instructions
- Copy-to-clipboard for account number, amount, and reference
- WhatsApp and Email verification flow
- WHMCS default Pay Now button integration

## Author

Built for the Bangladesh hosting market. Designed to be simple, clean, and user-friendly for customers unfamiliar with technical payment flows.
