*** Settings ***
Documentation     A page object to use in Group creation tests.
Library           SeleniumLibrary
Resource          ../common.resource

*** Variables ***
${CREATE GROUP PAGE URL}    ${ROOT URL}/group/create

*** Keywords ***
Create Group Page Should Be Open
    Location Should Be    ${CREATE GROUP PAGE URL}
    
Go To Create Group Page
    Go Authenticated To    ${CREATE GROUP PAGE URL}