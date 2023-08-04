*** Settings ***
Documentation     A page object to use in Accounts QR code tests.
...
Library           SeleniumLibrary
Resource          ../common.resource

*** Variables ***
${SHOW ACCOUNT QRCODE PAGE URL}    ${ROOT URL}/account/1/qrcode

*** Keywords ***
Show Account Qrcode Page Should Be Open
    Location Should Be    ${SHOW ACCOUNT QRCODE PAGE URL}
    
Go To Show Account Qrcode Page
    Go Authenticated To    ${SHOW ACCOUNT QRCODE PAGE URL}