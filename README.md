# UMH Connector for Dolibarr

[![License: GPL v3](https://img.shields.io/badge/License-GPLv3-blue.svg)](https://www.gnu.org/licenses/gpl-3.0)
[![Dolibarr](https://img.shields.io/badge/Dolibarr-14%2B-orange)](https://www.dolibarr.org)

Connect your Dolibarr customers to **[Unified Messenger Hub](https://messengerhub.de)** — send and receive WhatsApp & Telegram messages directly from the customer card.

---

## What it does

- **Adds a "UMH Messenger" tab** to every customer card (Thirdparties)
- **Auto-creates extrafields** for WhatsApp number and Telegram Chat-ID on install
- **One-click button** — opens the matching conversation in UMH directly
- Works seamlessly with the UMH tab reuse feature (`window.name`)

## Requirements

- Dolibarr ≥ 14.0
- PHP ≥ 7.4
- An active account at [messengerhub.de](https://messengerhub.de)

## Installation

1. Download or clone this repository
2. Copy the `umh_connector` folder to `dolibarr/htdocs/custom/umh_connector/`
3. In Dolibarr: **Home → Setup → Modules/Applications** → search for **UMH Connector** → activate
4. Go to **Setup → UMH Connector** → enter your UMH instance URL

## Configuration

| Setting | Default | Description |
|---|---|---|
| UMH App URL | `https://saas.messengerhub.de` | URL of your UMH SaaS instance |

## How it works

### WhatsApp
Customer lookup uses the **standard phone field** in Dolibarr. Make sure the customer's phone number is stored in E.164 format (e.g. `+49 123 456789`).

### Telegram
The module adds a `telegram_chat_id` extrafield. The numeric Chat-ID is visible in the UMH contact profile and must be entered once per customer.

## Screenshots

**UMH Messenger tab in the customer card:**

> WhatsApp and Telegram cards side by side, each showing the stored ID and an "Open Conversation" button.

## License

GNU General Public License v3.0 — see [LICENSE](LICENSE)

## Author

**Vitalij Haun IT HUB** · [messengerhub.de](https://messengerhub.de) · [info@messengerhub.de](mailto:info@messengerhub.de)
