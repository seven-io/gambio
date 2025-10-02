<img src="https://www.seven.io/wp-content/uploads/Logo.svg" width="250" />

# Seven Bulk Messaging Module for Gambio 4.x

Send bulk SMS and text-to-speech voice messages directly to your customers from your Gambio admin panel.

## Features

- **Bulk SMS** - Send personalized text messages to multiple customers
- **Text-to-Speech Calling** - Send automated voice messages
- **Customer Filtering** - Target specific customer groups
- **Message Personalization** - Use dynamic placeholders for customer data
- **Performance Tracking** - Track URL clicks in messages
- **Bilingual** - Full English and German translations

## Requirements

- Gambio 4.x
- PHP ≥ 5.6
- Seven.io API account ([sign up here](https://www.seven.io))

## Installation

### Option 1: From Release (Recommended)

1. Download the [latest release](https://github.com/seven-io/gambio/releases/latest/download/seven-gambio_latest.zip)
2. Extract the archive to `/<path>/<to>/<gambio>/GXModules`
3. Continue with [Activation](#activation)

### Option 2: From Source

```bash
cd /<path>/<to>/<gambio>/GXModules && mkdir Seven && cd Seven
git clone https://github.com/seven-io/gambio Seven && cd Seven
composer update
```

### Activation

1. Navigate to **Gambio Admin → Module Center**
2. Find and install the **Seven** module
3. Click **Edit** and insert your Seven.io **API key**
4. Click **Save**

## Usage

1. Navigate to **Customers → Send SMS** in your Gambio admin panel
2. Select message type: **SMS** or **Text-to-Speech**
3. (Optional) Filter by customer group
4. Customize sender ID (max 11 alphanumeric or 16 numeric characters)
5. Compose your message using placeholders
6. Click **Send**

### Message Placeholders

Personalize messages with customer data using `{{placeholder}}` syntax:

| Placeholder | Description | Example |
|------------|-------------|---------|
| `{{firstname}}` | Customer's first name | John |
| `{{lastname}}` | Customer's last name | Doe |
| `{{email}}` | Email address | john@example.com |
| `{{telephoneNumber}}` | Phone number | +49123456789 |
| `{{customerNumber}}` | Customer number | 12345 |
| `{{id}}` | Customer ID | 67890 |
| `{{isGuest}}` | Guest status | true/false |
| `{{faxNumber}}` | Fax number | +49987654321 |
| `{{vatNumber}}` | VAT number | DE123456789 |
| `{{vatNumberStatus}}` | VAT status | 1 |
| `{{customerStatusId}}` | Customer status ID | 2 |

**Example:**
```
Hi {{firstname}} {{lastname}}! Your order #{{customerNumber}} is ready for pickup.
Visit us or call {{telephoneNumber}} for delivery options.
```

### Advanced Options

#### SMS Options
- **Flash SMS** - Display message directly on receiver's screen without saving
- **Performance Tracking** - Track URL clicks in your message
- **Label** - Organize messages with custom labels (max 100 characters)
- **Foreign ID** - Add external reference ID (max 64 characters: `a-z, A-Z, 0-9, .-_@`)

#### Voice Options
- **XML Support** - Enable for XML-formatted voice messages
- **Character Limit** - Up to 10,000 characters for voice (1,520 for SMS)

## API Documentation

For detailed API documentation, visit [Seven.io API Docs](https://www.seven.io/en/docs/gateway/http-api/).

## Support

Need help? Feel free to [contact us](https://www.seven.io/en/company/contact/).

## License

[![MIT](https://img.shields.io/badge/License-MIT-teal.svg)](LICENSE)
