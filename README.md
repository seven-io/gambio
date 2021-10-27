![Sms77.io Logo](https://www.sms77.io/wp-content/uploads/2019/07/sms77-Logo-400x79.png "Sms77.io Logo")

Module for bulk messaging in Gambio 4.x installations.

# Installation

*From release - the easy way*

1. Download
   the [latest release](https://github.com/sms77io/gambio/releases/latest/download/sms77-gambio_latest.zip)
2. Extract archive to `/<path>/<to>/<gambio>/GXModules`

*From source*

1. `cd /<path>/<to>/<gambio>/GXModules && mkdir Sms77 && cd Sms77`
2. `git clone https://github.com/sms77io/gambio Sms77 && cd Sms77`
3. `composer update`

*Common steps*

1. Gambio Admin: Go to `Module Center` and install `Sms77`
2. Click `Edit`, insert `API key` and press `Save`
3. Start sending via `Customers->Send SMS`

## Functionalities

- Bulk SMS & Bulk text-to-speech calling
  - Filter by customer group

### Placeholders (for customer objects)

- customerNumber
- customerStatusId
- email
- faxNumber
- firstname
- id
- isGuest
- lastname
- telephoneNumber
- vatNumber
- vatNumberStatus

Usage: `{{isGuest}}` resolves to "false" if the customer is a registered user, "true"
otherwise.

#### Translations

- English
- German

##### Support

Need help? Feel free to [contact us](https://www.sms77.io/en/company/contact/).

[![MIT](https://img.shields.io/badge/License-MIT-teal.svg)](./LICENSE)
