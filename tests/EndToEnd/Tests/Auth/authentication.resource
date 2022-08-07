*** Settings ***
Documentation     A resource file for authentication tests.
Resource          ../../common.resource

*** Variables ***
${VALID EMAIL CASED}     TESTING@2fauth.app
${VALID PASSWORD CONFIRMATION}    password

${INVALID USERNAME TOO LONG}     veryLongUsernameeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeee
${INVALID EMAIL MALFORMED}     emailWithNoAt
${INVALID EMAIL DOES NOT EXIST}     missing.user@example.com
${INVALID EMAIL TOO LONG}     veryLongEmailAddressveryLongEmailAddressveryLongEmailAddressveryLongEmailAddressveryLongEmailAddressveryLongEmailAddressveryLongEmailAddressveryLongEmailAddressveryLongEmailAddressveryLongEmailAddressveryLongEmailAddressveryLongEmailAddress@verylongdomain.com
${INVALID PASSWORD}    notTheGoodPassword
${INVALID PASSWORD TOO SHORT}    pwd