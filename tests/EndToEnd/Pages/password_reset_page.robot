*** Settings ***
Documentation     A page object to use in password recovery tests.
...
Library           SeleniumLibrary
Resource          ../common.resource

*** Variables ***
${PASSWORD RESET PAGE URL}    ${ROOT URL}/user/password/reset
${EMAIL FIELD}    emlEmail
${EMAIL FIELD ERROR}    valErrorEmail
${PASSWORD FIELD}    pwdPassword
${PASSWORD FIELD ERROR}    valErrorPassword
${TOKEN FIELD ERROR}    valErrorToken
${SUBMIT BUTTON}    btnSubmit
${CONTINUE BUTTON}    btnContinue
${CANCEL BUTTON}    btnCancel
${TOGGLE PASSWORD VISIBILITY BUTTON}    btnTogglePassword

*** Keywords ***
Password Reset Page Should Be Open
    Location Should Be    ${PASSWORD RESET PAGE URL}

Go To Password Reset Page
    [Arguments]    ${email}    ${token}
    Go To    ${PASSWORD RESET PAGE URL}?email=${email}&token=${token}

Submit Data To Password Reset Form
    [Arguments]    ${new password}
    Input Text    ${PASSWORD FIELD}     ${new password}
    Click Button    ${SUBMIT BUTTON}

Email Field Should Show An Error
    Field Should Show An Error    ${EMAIL FIELD ERROR}
    
New Password Field Should Show An Error
    Field Should Show An Error    ${PASSWORD FIELD ERROR}
    
Token Field Should Show An Error
    Field Should Show An Error    ${TOKEN FIELD ERROR}