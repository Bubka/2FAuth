*** Settings ***
Documentation     A page object to use in 404 page tests.
...
Library           SeleniumLibrary
Resource          ../common.resource

*** Variables ***
${404 PAGE URL}    ${ROOT URL}/404

*** Keywords ***
404 Page Should Be Open
    Location Should Be    ${404 PAGE URL}

Go To 404 Page
    Go To    ${404 PAGE URL}
