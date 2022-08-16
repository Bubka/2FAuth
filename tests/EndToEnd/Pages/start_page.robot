*** Settings ***
Documentation     A page object to use in 2FA accounts acquisition tests.
...
Library           SeleniumLibrary
Resource          ../common.resource

*** Variables ***
${START PAGE URL}    ${ROOT URL}/start

*** Keywords ***
Start Page Should Be Open
    Location Should Be    ${START PAGE URL}

Go To Start Page
    Go Authenticated To    ${START PAGE URL}
