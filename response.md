## Response payload

IPN Variables: https://developer.paypal.com/docs/classic/ipn/integration-guide/IPNandPDTVariables/

### Raw data

```
cmd=_notify-validate&payment_type=instant&payment_date=Sat+Apr+23+2016+02%3A11%3A22+GMT-0600+%28CST%29&payment_status=Completed&address_status=confirmed&payer_status=verified&first_name=John&last_name=Smith&payer_email=buyer%40paypalsandbox.com&payer_id=TESTBUYERID01&address_name=John+Smith&address_country=United+States&address_country_code=US&address_zip=95131&address_state=CA&address_city=San+Jose&address_street=123+any+street&business=seller%40paypalsandbox.com&receiver_email=seller%40paypalsandbox.com&receiver_id=seller%40paypalsandbox.com&residence_country=US&item_name1=something&item_number1=AK-1234&quantity=5&tax=2.02&mc_currency=USD&mc_fee=0.44&mc_gross=12.34&mc_gross_1=12.34&mc_handling=2.06&mc_handling1=1.67&mc_shipping=3.02&mc_shipping1=1.02&txn_type=cart&txn_id=282087658&notify_version=2.1&custom=xyz123&invoice=abc1234&test_ipn=1&verify_sign=AFcWxV21C7fd0v3bYYYRCpSSRl31AF4yHwpWJPCGdoh.Y-wQ4fyq2BQi
```

### Split data

```yaml
mc_gross:20.00
protection_eligibility:Ineligible
address_status:unconfirmed
payer_id:5N2F36LAQ3AFG
tax:0.00
address_street:1 Main St
payment_date:19:14:02 Jan 18, 2016 PST
payment_status:Pending
charset:windows-1252
address_zip:95131
first_name:Ariel
address_country_code:US
address_name:Ariel Fierro
notify_version:3.8
custom:
payer_status:unverified
address_country:United States
address_city:San Jose
quantity:1
payer_email:buyer@colectivopyme.com
verify_sign:AFcWxV21C7fd0v3bYYYRCpSSRl31AWK2Jup4cjQEmXZxTDbEx-KhsBZq
txn_id:9JH877021V814904L
payment_type:instant
last_name:Fierro
address_state:CA
receiver_email:info@colectivopyme.com
pending_reason:unilateral
txn_type:web_accept
item_name:Mercadeo en facebook para microempresarios de bisutería y joyería
mc_currency:USD
item_number:
residence_country:US
test_ipn:1
handling_amount:0.00
transaction_subject:
payment_gross:20.00
shipping:0.00
auth:AZ.cLuF6Fg3g1s7aKDKZkQJH5ftccy1e6E1ifdBs2Lo3hMMLBJ7tNiCx-OjYjMrJwoeS7QVSezlavdS96zhHWzg

```
