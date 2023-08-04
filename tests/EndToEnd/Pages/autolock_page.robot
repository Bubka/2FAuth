*** Settings ***
Documentation     A page object to use in autolock tests.
...
Library           SeleniumLibrary
Resource          ../common.resource

*** Variables ***
${AUTOLOCK PAGE URL}    ${ROOT URL}/autolock

*** Keywords ***
Autolock Page Should Be Open
    Location Should Be    ${AUTOLOCK PAGE URL}

Go To Autolock Page
    Go To    ${AUTOLOCK PAGE URL}
