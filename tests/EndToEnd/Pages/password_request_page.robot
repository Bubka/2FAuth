*** Settings ***
Documentation     A page object to use in password recovery tests.
...
Library           SeleniumLibrary
Resource          ../common.resource

*** Variables ***
${PASSWORD REQUEST PAGE URL}    ${ROOT URL}/password/request
${EMAIL FIELD}    emlEmail
${EMAIL FIELD ERROR}    valErrorEmail
${SUBMIT BUTTON}    btnSendResetPwd
${CANCEL BUTTON}    btnCancel

*** Keywords ***
Password Request Page Should Be Open
    Location Should Be    ${PASSWORD REQUEST PAGE URL}

Go To Password Request Page
    Go To    ${PASSWORD REQUEST PAGE URL}

Submit Data To Password Request Form
    [Arguments]    ${email}
    Input Text    ${EMAIL FIELD}     ${email}
    Click Button    ${SUBMIT BUTTON}

Email Field Should Show An Error
    Field Should Show An Error    ${EMAIL FIELD ERROR}