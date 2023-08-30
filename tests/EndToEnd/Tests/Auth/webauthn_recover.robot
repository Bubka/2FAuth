*** Settings ***
Documentation     A test suite containing tests related to webauthn account recover.
Suite Setup       run Keywords
...                   Open Custom Browser
...                   AND    Play Logout Workflow
# ...                   AND    Declare Webauthn Device Lost And Come Back
Suite Teardown    Close All Browsers
Resource          ../../Pages/webauthn_recover_page.robot
Resource          ../../Pages/webauthn_lost_page.robot
Resource          ../../Pages/login_page.robot
Resource          authentication.resource
Resource    ../../Pages/password_request_page.robot

*** Variables ***
${TOKEN}    token
${INVALID TOKEN}    token

*** Test Cases ***
Invalid Token Should Be Rejected
    [Template]    Token Submit Should Fail
    ${EMPTY}
    ${INVALID TOKEN}

# Invalid Password Is Rejected
#     [Template]    Password Submit Should Fail
#     ${EMPTY}
#     ${INVALID PASSWORD}

Reset Passwork Link Should Be Visible
    Go To Webauthn Recover Page    \    \
    Element Should Be Visible    ${RESET PASSWORD LINK}
    Click Link    ${RESET PASSWORD LINK}
    Password Request Page Should Be Open

Webauthn Recover Form Can Be Quit
    Go To Webauthn Recover Page    \    \
    Click Element    ${CANCEL BUTTON}
    Login Page Should Be Open
    
*** Keywords ***
Token Submit Should Fail
    [Arguments]    ${bad token}
    Go To Webauthn Recover Page    ${ADMIN EMAIL}    ${bad token}
    Submit Data To Webauthn Recover Form     ${PASSWORD}
    An Error Notification Should Appear

Password Submit Should Fail
    [Arguments]    ${bad password}
    Go To Webauthn Recover Page    ${ADMIN EMAIL}    ${TOKEN}
    Submit Data To Webauthn Recover Form     ${bad password}
    webauthn_recover_page.Password Field Should Show An Error

Declare Webauthn Device Lost And Come Back
    Go To Webauthn Device Lost Page
    Submit Data To Webauthn Lost Form    ${ADMIN EMAIL}
    Go To Webauthn Recover Page    ${ADMIN EMAIL}    ${TOKEN}
