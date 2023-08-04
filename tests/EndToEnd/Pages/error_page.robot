*** Settings ***
Documentation     A page object to use in About page tests.
...
Library           SeleniumLibrary
Resource          ../common.resource

*** Variables ***
${ERROR PAGE URL}    ${ROOT URL}/error

*** Keywords ***
Error Page Should Be Open
    Location Should Be    ${ERROR PAGE URL}

Go To Error Page
    Go To    ${ERROR PAGE URL}
