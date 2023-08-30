*** Settings ***
Documentation     A page object to use in 2FA accounts acquisition tests.
...
Library           SeleniumLibrary
Resource          ../common.resource

*** Variables ***
${START PAGE URL}    ${ROOT URL}/start

${IMPORT BUTTON}    btnImport

*** Keywords ***
Start Page Should Be Open
    Location Should Be    ${START PAGE URL}

Go To Start Page
    Go Authenticated To    ${START PAGE URL}
    Wait Until Page Contains Element     ${BACK BUTTON}
    Set Footer As Static

Choose To Import Accounts
    Click Element    ${IMPORT BUTTON}
