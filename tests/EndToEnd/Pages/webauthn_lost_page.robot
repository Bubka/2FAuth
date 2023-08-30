*** Settings ***
Documentation     A page object to use in webauthn recovery tests.
...
Library           SeleniumLibrary
Resource          ../common.resource

*** Variables ***
${WEBAUTHN LOST PAGE URL}    ${ROOT URL}/webauthn/lost
${EMAIL FIELD}    emlEmail
${EMAIL FIELD ERROR}    valErrorEmail
${SUBMIT BUTTON}    btnSubmit

*** Keywords ***
Webauthn Device Lost Page Should Be Open
    Location Should Be    ${WEBAUTHN LOST PAGE URL}

Go To Webauthn Device Lost Page
    Go To    ${WEBAUTHN LOST PAGE URL}

Submit Data To Webauthn Lost Form
    [Arguments]    ${email}
    Input Text    ${EMAIL FIELD}     ${email}
    Click Button    ${SUBMIT BUTTON}

Email Field Should Show An Error
    Field Should Show An Error    ${EMAIL FIELD ERROR}