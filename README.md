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
2. Copy the module folder to `dolibarr/htdocs/custom/umhconnector/` — the folder **must** be named `umhconnector` (the repository folder may be named differently). Alternatively upload the release ZIP via **Setup → Modules/Applications → Deploy/install external app/module**
3. In Dolibarr: **Home → Setup → Modules/Applications** → search for **UMH Connector** → activate
4. Go to **Setup → UMH Connector** → enter your UMH instance URL

## Updating

> ⚠️ **Updating from 1.0.0: do NOT deactivate the module before the new files are in place.** In version 1.0.0, deactivating the module deletes the WhatsApp/Telegram extrafields including all stored values. Version 1.1.0 no longer does this.

1. Back up your database (**Home → Admin tools → Backup**)
2. Upload the new ZIP via **Setup → Modules/Applications → Deploy/install external app/module** while the module stays **enabled** (the existing module folder is replaced)
3. Only now **deactivate and immediately re-activate** the module — this registers the new permission "UMH-Tab bearbeiten" (edit) introduced in 1.1.0
4. Check: stored numbers are still shown in the "UMH Messenger" tab and saving works; if a user cannot save, grant "UMH-Tab bearbeiten" under **Users → Permissions**

## Configuration

| Setting | Default | Description |
|---|---|---|
| UMH App URL | `https://saas.messengerhub.de` | URL of your UMH SaaS instance |

## How it works

### WhatsApp
- **The "Open Conversation" button always requires `umh_whatsapp` to be filled in** — even if it's the same as the phone field. There is no fallback here on purpose: a business-initiated WhatsApp template costs money, and silently sending it to a landline number that isn't on WhatsApp means the customer never receives it while you wait for a reply that will never come.
- **Incoming-message recognition** (UMH's "known customer" detection, and linking new leads/quotes/tickets/orders to the right customer) is more lenient: it checks `umh_whatsapp` first, then falls back to the standard phone field — there's no sending risk there, only lookup.
- Make sure whichever number is used is stored in E.164 format (e.g. `+49 123 456789`).

### Telegram
The module adds a `telegram_chat_id` extrafield. The numeric Chat-ID is visible in the UMH contact profile and must be entered once per customer.

## Screenshots

**UMH Messenger tab in the customer card:**

> WhatsApp and Telegram cards side by side, each showing the stored ID and an "Open Conversation" button.

## License

GNU General Public License v3.0 — see [LICENSE](LICENSE)

## Author

**Vitalij Haun IT HUB** · [messengerhub.de](https://messengerhub.de) · [info@messengerhub.de](mailto:info@messengerhub.de)
