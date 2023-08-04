*** Settings ***
Documentation     A page object to use in Capture tests.
Library           SeleniumLibrary
Resource          ../common.resource

*** Variables ***
${CAPTURE PAGE URL}    ${ROOT URL}/capture

*** Keywords ***
Capture Page Should Be Open
    Location Should Be    ${CAPTURE PAGE URL}
    
Go To Capture Page
    Go Authenticated To    ${CAPTURE PAGE URL}