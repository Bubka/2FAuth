*** Settings ***
Documentation     A page object to use in User Options settings tests.
...
Library           SeleniumLibrary
Resource          ../common.resource

*** Variables ***
${OPTIONS SETTINGS PAGE URL}    ${ROOT URL}/settings/options

*** Keywords ***
Options Settings Page Should Be Open
    Location Should Be    ${OPTIONS SETTINGS PAGE URL}

Go To Options Settings Page
    Go Authenticated To    ${OPTIONS SETTINGS PAGE URL}
