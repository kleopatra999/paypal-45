# paypal

## JS buttons implementation

**Reference:** https://paypal.github.io/JavaScriptButtons/

- script submits form data to paypal
	- paypal sends response data to 'transaction page' (data-callback url)
	- transaction page modifies data and send it back to paypal
	- paypal validates data and sends status response to 'transaction page'
- if status is valid script redirects user to 'return page' (data-return url)

## Button types

- `data-button="buynow"`
- `data-button="cart"`
- `data-button="donate"`
- `data-button="subscribe"`

## IPN implementation

- set `data-callback="/path/to/paypal-ipn.php"` <= transaciton logic goes here
