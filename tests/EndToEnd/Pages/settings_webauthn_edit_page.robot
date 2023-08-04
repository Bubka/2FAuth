*** Settings ***
Documentation     A page object to use in WebAuthn rename device tests.
...
Library           SeleniumLibrary
Resource          ../common.resource

*** Variables ***
${RENAME WEBAUTHN DEVICE SETTINGS PAGE URL}    ${ROOT URL}/settings/webauthn/1/edit

*** Keywords ***
Rename Webauthn Device Settings Page Should Be Open
    Location Should Be    ${RENAME WEBAUTHN DEVICE SETTINGS PAGE URL}

Go To Rename Webauthn Device Settings Page
    Go Authenticated To    ${RENAME WEBAUTHN DEVICE SETTINGS PAGE URL}
