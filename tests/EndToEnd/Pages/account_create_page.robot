*** Settings ***
Documentation     A page object to use in 2FA accounts creation tests.
...
Library           SeleniumLibrary
Resource          ../common.resource

*** Variables ***
${CREATE ACCOUNT PAGE URL}    ${ROOT URL}/account/create

*** Keywords ***
Create Account Page Should Be Open
    Location Should Be    ${CREATE ACCOUNT PAGE URL}

Go To Create Account Page
    Go Authenticated To    ${CREATE ACCOUNT PAGE URL}
