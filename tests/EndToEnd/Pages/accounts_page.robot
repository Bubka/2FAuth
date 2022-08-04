*** Settings ***
Documentation     A page object to use in Accounts tests.
...
Library           SeleniumLibrary
Resource          ../common.resource

*** Variables ***
&{ACCOUNTS_PAGE}    url=http://${SERVER}/accounts    title=Accounts

*** Keywords ***
Accounts Page Should Be Open
    Page Should Be Open    ${ACCOUNTS_PAGE}