<img src="https://www.seven.io/wp-content/uploads/Logo.svg" width="250" />

Module for bulk messaging in Gambio 4.x installations.

# Installation

*From release - the easy way*

1. Download
   the [latest release](https://github.com/seven-io/gambio/releases/latest/download/seven-gambio_latest.zip)
2. Extract archive to `/<path>/<to>/<gambio>/GXModules`

*From source*

1. `cd /<path>/<to>/<gambio>/GXModules && mkdir Seven && cd Seven`
2. `git clone https://github.com/seven-io/gambio Seven && cd Seven`
3. `composer update`

*Common steps*

1. Gambio Admin: Go to `Module Center` and install `Seven`
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

Need help? Feel free to [contact us](https://www.seven.io/en/company/contact/).

[![MIT](https://img.shields.io/badge/License-MIT-teal.svg)](LICENSE)
