*** Settings ***
Documentation     A page object to use in About page tests.
...
Library           SeleniumLibrary
Resource          ../common.resource

*** Variables ***
${ABOUT PAGE URL}    ${ROOT URL}/about

*** Keywords ***
About Page Should Be Open
    Location Should Be    ${ABOUT PAGE URL}

Go To About Page
    Go To    ${ABOUT PAGE URL}
