*** Settings ***
Documentation     A page object to use in WebAuthn settings tests.
...
Library           SeleniumLibrary
Resource          ../common.resource

*** Variables ***
${WEBAUTHN SETTINGS PAGE URL}    ${ROOT URL}/settings/webauthn

*** Keywords ***
Webauthn Settings Page Should Be Open
    Location Should Be    ${WEBAUTHN SETTINGS PAGE URL}

Go To Webauthn Settings Page
    Go Authenticated To    ${WEBAUTHN SETTINGS PAGE URL}
