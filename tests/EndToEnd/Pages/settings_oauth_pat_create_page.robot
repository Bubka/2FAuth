*** Settings ***
Documentation     A page object to use in OAuth settings tests.
...
Library           SeleniumLibrary
Resource          ../common.resource

*** Variables ***
${CREATE OAUTH PAT SETTINGS PAGE URL}    ${ROOT URL}/settings/oauth/pat/create

*** Keywords ***
Create OAuth Pat Settings Page Should Be Open
    Location Should Be    ${CREATE OAUTH PAT SETTINGS PAGE URL}

Go To Create OAuth Pat Settings Page
    Go Authenticated To    ${CREATE OAUTH PAT SETTINGS PAGE URL}
