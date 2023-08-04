*** Settings ***
Documentation     A page object to use in Accounts import tests.
...
Library           SeleniumLibrary
Resource          ../common.resource

*** Variables ***
${IMPORT ACCOUNTS PAGE URL}    ${ROOT URL}/account/import

*** Keywords ***
Import Accounts Page Should Be Open
    Location Should Be    ${IMPORT ACCOUNTS PAGE URL}
    
Go To Import Accounts Page
    Go Authenticated To    ${IMPORT ACCOUNTS PAGE URL}