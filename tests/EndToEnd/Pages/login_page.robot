*** Settings ***
Documentation     A page object to use in Login tests.
...
Library           SeleniumLibrary
Resource          ../common.resource

*** Variables ***
&{LOGIN_PAGE}      url=http://${SERVER}/login    title=Login
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

*** Keywords ***
Open Browser To Legacy Login Page
    Open Browser To Page    ${LOGIN_PAGE}
    Show Legacy Form

Go To Legacy Login Page
    Go To Page    ${LOGIN_PAGE}
    Show Legacy Form

Submit Credentials To Legacy Form Login
    [Arguments]    ${username}    ${password}
    Input Text    ${EMAIL FIELD}    ${username}
    Input Text    ${PASSWORD FIELD}     ${password}
    Click Button    ${SIGN IN BUTTON}

Email Field Should Show An Error
    Field Should Show An Error    ${EMAIL FIELD ERROR}

Password Field Should Show An Error
    Field Should Show An Error    ${PASSWORD FIELD ERROR}

Show Legacy Form
    ${is_not_visible}=  Run Keyword And Return Status    Element Should Be Visible   ${SIGN IN WITH LOGIN PASSWORD LINK}
    Run Keyword If    ${is_not_visible}    Click Link    ${SIGN IN WITH LOGIN PASSWORD LINK}
    Element Should Be Visible    ${LEGACY FORM}

Show Webauthn Form
    ${is_not_visible}=  Run Keyword And Return Status    Element Should Be Visible   ${SIGN IN WITH WEBAUTHN LINK}
    Run Keyword If    ${is_not_visible}    Click Link    ${SIGN IN WITH WEBAUTHN LINK}
    Element Should Not Be Visible    ${LEGACY FORM}
