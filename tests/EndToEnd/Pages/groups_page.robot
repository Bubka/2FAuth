*** Settings ***
Documentation     A page object to use in Groups tests.
Library           SeleniumLibrary
Resource          ../common.resource

*** Variables ***
${GROUPS PAGE URL}    ${ROOT URL}/groups

*** Keywords ***
Groups Page Should Be Open
    Location Should Be    ${GROUPS PAGE URL}
    
Go To Groups Page
    Go Authenticated To    ${GROUPS PAGE URL}