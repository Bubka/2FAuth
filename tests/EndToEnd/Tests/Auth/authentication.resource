*** Settings ***
Documentation     A resource file for authentication tests.
Resource          ../../common.resource

*** Variables ***
${NEW USER NAME}              bob
${UNREGISTERED EMAIL}         unregistered@2fauth.app
${VALID ADMIN EMAIL CASED}    TESTINGADMIN@2fauth.app
${VALID USER EMAIL CASED}     TESTINGUSER@2fauth.app

${INVALID USERNAME TOO LONG}     veryLongUsernameeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeee
${INVALID EMAIL MALFORMED}     emailWithNoAt
${INVALID EMAIL DOES NOT EXIST}     missing.user@example.com
${INVALID EMAIL TOO LONG}     veryLongEmailAddressveryLongEmailAddressveryLongEmailAddressveryLongEmailAddressveryLongEmailAddressveryLongEmailAddressveryLongEmailAddressveryLongEmailAddressveryLongEmailAddressveryLongEmailAddressveryLongEmailAddressveryLongEmailAddress@verylongdomain.com
${INVALID PASSWORD}    notTheGoodPassword
${INVALID PASSWORD TOO SHORT}    pwd