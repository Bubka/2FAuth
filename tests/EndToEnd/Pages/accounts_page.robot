*** Settings ***
Documentation     A page object to use in Accounts tests.
...
Library           SeleniumLibrary
Resource          ../common.resource

*** Variables ***
${ACCOUNTS PAGE URL}    ${ROOT URL}/accounts

*** Keywords ***
Accounts Page Should Be Open
    Location Should Be    ${ACCOUNTS PAGE URL}
    
Go To Accounts Page
    Go Authenticated To    ${ACCOUNTS PAGE URL}