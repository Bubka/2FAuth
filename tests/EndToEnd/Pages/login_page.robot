*** Settings ***
Documentation     A page object to use in Login tests.
...
Library           SeleniumLibrary
Resource          ../common.resource

*** Variables ***
${LOGIN PAGE URL}    ${ROOT URL}/login
${EMAIL FIELD}    emlEmail
${EMAIL FIELD ERROR}    valErrorEmail
${PASSWORD FIELD}    pwdPassword
${PASSWORD FIELD ERROR}    valErrorPassword
${LEGACY FORM}    frmLegacyLogin
${SIGN IN BUTTON}    btnSignIn
${CONTINUE WITH WEBAUTHN BUTTON}    btnContinue
${SIGN IN WITH WEBAUTHN LINK}    lnkSignWithWebauthn
${SIGN IN WITH LOGIN PASSWORD LINK}    lnkSignWithLegacy
${RECOVER YOUR ACCOUNT LINK}    lnkRecoverAccount
${RESET PASSWORD LINK}    lnkResetPwd
${PUNCHLINE TEXT}    punchline

*** Keywords ***

Go To Legacy Login Page
    Go To    ${LOGIN PAGE URL}
    Show Legacy Form

Go To Webauthn Login Page
    Go To    ${LOGIN PAGE URL}
    Show Webauthn Form

Login Page Should Be Open
    Location Should Be    ${LOGIN PAGE URL}

Submit Credentials To Legacy Form Login
    [Arguments]    ${email}    ${password}
    Input Text    ${EMAIL FIELD}    ${email}
    Input Text    ${PASSWORD FIELD}     ${password}
    Scroll To Bottom
    Click Button    ${SIGN IN BUTTON}

Email Field Should Show An Error
    Field Should Show An Error    ${EMAIL FIELD ERROR}

Password Field Should Show An Error
    Field Should Show An Error    ${PASSWORD FIELD ERROR}

Show Legacy Form
    ${is_not_visible}=  Run Keyword And Return Status    Element Should Be Visible   ${SIGN IN WITH LOGIN PASSWORD LINK}
    Run Keyword If    ${is_not_visible}    Click Link    ${SIGN IN WITH LOGIN PASSWORD LINK}

Legacy Form Should Be Visible
    Element Should Be Visible    ${LEGACY FORM}

Show Webauthn Form
    ${is_not_visible}=  Run Keyword And Return Status    Element Should Be Visible   ${SIGN IN WITH WEBAUTHN LINK}
    Run Keyword If    ${is_not_visible}    Click Link    ${SIGN IN WITH WEBAUTHN LINK}

Webauthn Form Should Be Visible
    Element Should Not Be Visible    ${LEGACY FORM}

User Should Be Welcomed
    Element Should Contain    ${PUNCHLINE TEXT}    ${USERNAME}