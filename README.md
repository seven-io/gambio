<p align="center">
  <img src="https://www.seven.io/wp-content/uploads/Logo.svg" width="250" alt="seven logo" />
</p>

<h1 align="center">seven SMS for Gambio</h1>

<p align="center">
  Bulk SMS and text-to-speech for <a href="https://www.gambio.com/">Gambio</a> 4.x via the seven gateway.
</p>

<p align="center">
  <a href="LICENSE"><img src="https://img.shields.io/badge/License-MIT-teal.svg" alt="MIT License" /></a>
  <img src="https://img.shields.io/badge/Gambio-4.x-blue" alt="Gambio 4.x" />
  <img src="https://img.shields.io/badge/PHP-5.6%2B-purple" alt="PHP 5.6+" />
</p>

---

## Features

- **Bulk SMS** - Personalized text to multiple customers
- **Text-to-Speech Calls** - Automated voice messages
- **Customer Filtering** - Target specific customer groups
- **Message Placeholders** - `{{firstname}}`, `{{lastname}}`, etc.
- **Performance Tracking** - URL click tracking
- **Bilingual** - English and German translations bundled

## Prerequisites

- Gambio 4.x
- PHP 5.6+
- A [seven account](https://www.seven.io/) with API key ([How to get your API key](https://help.seven.io/en/developer/where-do-i-find-my-api-key))

## Installation

### Option 1: From Release (Recommended)

1. Download the [latest release](https://github.com/seven-io/gambio/releases/latest/download/seven-gambio_latest.zip).
2. Extract into `/<path>/<to>/<gambio>/GXModules`.

### Option 2: From Source

```bash
cd /<path>/<to>/<gambio>/GXModules && mkdir Seven && cd Seven
git clone https://github.com/seven-io/gambio Seven && cd Seven
composer update
```

### Activation

1. **Gambio Admin > Module Center**.
2. Install **Seven**.
3. Click **Edit**, paste your seven API key, click **Save**.

## Usage

1. **Customers > Send SMS**.
2. Pick message type (SMS / Text-to-Speech).
3. Optionally filter by customer group.
4. Customize sender ID (max 11 alphanumeric or 16 numeric characters).
5. Compose with placeholders.
6. Click **Send**.

### Placeholders

| Placeholder | Description |
|-------------|-------------|
| `{{firstname}}` | Customer's first name |
| `{{lastname}}` | Customer's last name |
| `{{email}}` | Email address |
| `{{telephoneNumber}}` | Phone number |
| `{{customerNumber}}` | Customer number |
| `{{id}}` | Customer ID |
| `{{isGuest}}` | Guest status |
| `{{faxNumber}}` | Fax number |
| `{{vatNumber}}` | VAT number |
| `{{vatNumberStatus}}` | VAT status |
| `{{customerStatusId}}` | Customer status ID |

Example:

```
Hi {{firstname}} {{lastname}}! Your order #{{customerNumber}} is ready for pickup.
```

### Advanced options

- **SMS:** Flash, performance tracking, custom label (max 100 chars), foreign ID (max 64 chars, `a-z A-Z 0-9 .-_@`)
- **Voice:** XML support, up to 10000 characters per message (vs 1520 for SMS)

## Support

Need help? Feel free to [contact us](https://www.seven.io/en/company/contact/) or [open an issue](https://github.com/seven-io/gambio/issues).

## License

[MIT](LICENSE)
