*** Settings ***
Documentation     A page object to use in 2FA account edition tests.
...
Library           SeleniumLibrary
Resource          ../common.resource

*** Variables ***

*** Keywords ***
Edit Page Should Be Open For Account
    [Arguments]    ${account id}
    Location Should Be    ${ROOT URL}/account/${account id}/edit

Go To Edit Page For Account
    [Arguments]    ${account id}
    Go Authenticated To    ${ROOT URL}/account/${account id}/edit
