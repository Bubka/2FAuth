*** Settings ***
Documentation     A page object to use in webauthn recovery tests.
...
Library           SeleniumLibrary
Resource          ../common.resource

*** Variables ***
${WEBAUTHN RECOVER PAGE URL}    ${ROOT URL}/webauthn/recover
${PASSWORD FIELD}    pwdPassword
${PASSWORD FIELD ERROR}    valErrorPassword
${REVOKE ALL CHECKBOX}    revokeAll
${SUBMIT BUTTON}    btnRecover
${CANCEL BUTTON}    btnCancel
${RESET PASSWORD LINK}    lnkResetPwd

*** Keywords ***
Webauthn Recover Page Should Be Open
    Location Should Be    ${WEBAUTHN RECOVER PAGE URL}

Go To Webauthn Recover Page
    [Arguments]    ${email}    ${token}
    Go To    ${WEBAUTHN RECOVER PAGE URL}?email=${email}&token=${token}

Submit Data To Webauthn Recover Form
    [Arguments]    ${password}
    Input Text    ${PASSWORD FIELD}     ${password}
    Click Button    ${SUBMIT BUTTON}

Password Field Should Show An Error
    Field Should Show An Error    ${PASSWORD FIELD ERROR}