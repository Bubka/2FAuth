*** Settings ***
Documentation     A page object to use in Registration tests.
...
Library           SeleniumLibrary
Resource          ../common.resource

*** Variables ***
${REGISTER PAGE URL}    ${ROOT URL}/register
${USERNAME FIELD}    txtName
${USERNAME FIELD ERROR}    valErrorName
${EMAIL FIELD}    emlEmail
${EMAIL FIELD ERROR}    valErrorEmail
${PASSWORD FIELD}    pwdPassword
${PASSWORD FIELD ERROR}    valErrorPassword
${PASSWORD CONFIRMATION FIELD}    pwdPassword_confirmation
${REGISTER BUTTON}    btnRegister
${REGISTER NEW DEVICE BUTTON}    btnRegisterNewDevice
${MAYBE LATER BUTTON}    btnMaybeLater
${SIGN IN LINK}    lnkSignIn

*** Keywords ***
Go To Register Page
    Go To    ${REGISTER PAGE URL}

Register Page Should Be Open
    Location Should Be    ${REGISTER PAGE URL}

Submit User Data To Registration Form
    [Arguments]    ${username}    ${email}    ${password}    ${password confirmation}
    Input Text    ${USERNAME FIELD}    ${username}
    Input Text    ${EMAIL FIELD}    ${email}
    Input Text    ${PASSWORD FIELD}     ${password}
    Input Text    ${PASSWORD CONFIRMATION FIELD}     ${password confirmation}
    Scroll To Bottom
    Click Button    ${REGISTER BUTTON}

Username Field Should Show An Error
    Field Should Show An Error    ${USERNAME FIELD ERROR}

Email Field Should Show An Error
    Field Should Show An Error    ${EMAIL FIELD ERROR}

Password Field Should Show An Error
    Field Should Show An Error    ${PASSWORD FIELD ERROR}

Postpone Webauthn Registration
    Click Element    ${MAYBE LATER BUTTON}