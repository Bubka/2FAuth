*** Settings ***
Documentation     A page object to use in WebAuthn settings tests.
...
Library           SeleniumLibrary
Resource          settings.resource
Resource          ../common.resource

*** Variables ***
${WEBAUTHN SETTINGS PAGE URL}    ${ROOT URL}/settings/webauthn

*** Keywords ***
Webauthn Settings Page Should Be Open
    Wait Until Location Is    ${WEBAUTHN SETTINGS PAGE URL}

Go To Webauthn Settings Page
    Go Authenticated To    ${WEBAUTHN SETTINGS PAGE URL}

Activate Webauthn Settings Tab
    Activate Settings Tab    ${WEBAUTHN TAB}
    Wait Until Page Does Not Contain Element    icnSpinner