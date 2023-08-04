*** Settings ***
Documentation     A page object to use in Group edition tests.
Library           SeleniumLibrary
Resource          ../common.resource

*** Variables ***
${EDIT GROUP PAGE URL}    ${ROOT URL}/group/1/edit

*** Keywords ***
Edit Page Should Be Open For Group
    [Arguments]    ${group id}
    Location Should Be    ${ROOT URL}/group/${group id}/edit
    
Go To Edit Page For Group
    [Arguments]    ${group id}
    Go Authenticated To    ${ROOT URL}/group/${group id}/edit