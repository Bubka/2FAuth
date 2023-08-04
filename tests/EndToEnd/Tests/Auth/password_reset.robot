*** Settings ***
Documentation     A test suite containing tests related to password reset.
Suite Setup       run Keywords
...                   Open Custom Browser
...                   AND    Play Logout Workflow
Suite Teardown    Close All Browsers
Resource          ../../Pages/password_reset_page.robot
Resource          ../../Pages/accounts_page.robot
Resource          ../../Pages/login_page.robot
Resource          authentication.resource

*** Variables ***
${NEW PASSWORD}    new_password
${INVALID NEW PASSWORD}    new
${TOKEN}    token
${INVALID TOKEN}    token

*** Test Cases ***
Invalid Email Is Rejected
    [Template]    Email Submit Should Fail
    ${EMPTY}
    ${UNREGISTERED EMAIL}
    ${INVALID EMAIL MALFORMED}
    
Invalid Password Is Rejected
    [Template]    Password Submit Should Fail
    ${EMPTY}
    ${INVALID NEW PASSWORD}

No Token Is Rejected
    Go To Password Reset Page    ${ADMIN EMAIL}    ${EMPTY}
    Submit Data To Password Reset Form     ${NEW PASSWORD}
    Token Field Should Show An Error

Invalid Token Is Rejected
    Go To Password Reset Page    ${ADMIN EMAIL}    ${INVALID TOKEN}
    Submit Data To Password Reset Form     ${NEW PASSWORD}
    password_reset_page.Email Field Should Show An Error

Password Reset Is Submitted Sucessfully
    Go To Password Reset Page    ${ADMIN EMAIL}    ${TOKEN}
    Element Attribute Value Should Be    emlEmail    value    ${ADMIN EMAIL}
#     Submit Data To Password Reset Form    ${NEW PASSWORD}
#     Wait Until Element Is Visible    css:#vueNotification .is-success    30s
#     Wait Until Element Is Visible    ${CONTINUE BUTTON}
#     Click Link    ${CONTINUE BUTTON}
#     Accounts Page Should Be Open

Reset Form Can Be Quit
    Go To Password Reset Page    ${ADMIN EMAIL}    ${TOKEN}
    Click Link    ${CANCEL BUTTON}
    Login Page Should Be Open
    
*** Keywords ***
Email Submit Should Fail
    [Arguments]    ${bad email}
    Go To Password Reset Page    ${bad email}    ${TOKEN}
    Submit Data To Password Reset Form     ${NEW PASSWORD}
    password_reset_page.Email Field Should Show An Error
    
Password Submit Should Fail
    [Arguments]    ${bad password}
    Go To Password Reset Page    ${ADMIN EMAIL}    ${TOKEN}
    Submit Data To Password Reset Form     ${bad password}
    password_reset_page.New Password Field Should Show An Error
