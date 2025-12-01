# Dokobit WS API PHP Examples

## How to start? 

Check more documentation at https://developers.dokobit.com

Request access token [here](https://www.dokobit.com/developers/request-token).

Enter API access token to example in 5th line.

Check Mobile ID and Smart-ID test data [here](https://dokobitbysignicat.zendesk.com/hc/en-us/articles/20067528968476-Mobile-ID-and-Smart-ID-test-data).

### Mobile-ID signing example

Enter phone number and personal code (from test data) at 7-8 lines or use as console command:

`php mobile_sign.php ./test.pdf {phone} {personalCode}`

### Mobile-ID identification example

Enter phone number and personal code (from test data) at 6-7 lines or use as console command:

`php mobile_login.php {phone} {personalCode}`

### Smart-ID signing example 

Enter country and personal code (from test data or of your Smart-ID DEMO App) at 6-7 lines or use as console command:

`php smart-id_sign.php {country} {personalCode}`

### Smart-ID identification example 

Enter country and personal code (from test data or of your Smart-ID DEMO App) at 6-7 lines or use as console command:

`php smart-id_login.php {country} {personalCode}`
