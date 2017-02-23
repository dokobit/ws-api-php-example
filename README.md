# ISIGN.io API PHP Examples

## How to start? 

Request access token at https://www.isign.io/services/contacts#request-access

Enter API access token to example in 5th line.

### Mobile-ID signing example
Enter your phone number and personal code (or use testing ones) at 7-8 lines or use as console command:

`php mobile_sign.php ./test.pdf {phone} {personalCode}`

### Smart-ID signing example 

Enter your country and personal code at 6-7 lines or use as console command:

`php smart-id_sign.php {country} {personalCode}`

### Smart-ID identification example 

Enter your country and personal code at 6-7 lines or use as console command:

`php smart-id_login.php {country} {personalCode}`

