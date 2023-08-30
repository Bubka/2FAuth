*** Settings ***
Documentation     A test suite containing tests related to webauthn device lost.
Suite Setup       run Keywords
...                   Open Custom Browser
...                   AND    Play Logout Workflow
Test Setup        Go To Webauthn Device Lost Page
Suite Teardown    Close All Browsers
Resource          ../../Pages/webauthn_lost_page.robot
Resource          authentication.resource

*** Test Cases ***
Invalid Email Should Be Rejected
    [Template]    Email Submit Should Fail
    ${EMPTY}
    ${UNREGISTERED EMAIL}
    ${INVALID EMAIL MALFORMED}

Webauthn Lost Is Submitted Sucessfully
    Submit Data To Webauthn Lost Form    ${ADMIN EMAIL}
    Wait Until Element Is Visible    css:#vueNotification .is-success    30s
    Webauthn Device Lost Page Should Be Open

New Request While Pending One Is Rejected
    Email Submit Should Fail    ${ADMIN EMAIL}

Webauthn Lost Form Can Be Quit
    Click Element    ${CANCEL BUTTON}
    Login Page Should Be Open
    
*** Keywords ***
Email Submit Should Fail
    [Arguments]    ${bad email}
    Submit Data To Webauthn Lost Form    ${bad email}
    webauthn_lost_page.Email Field Should Show An Error
    Webauthn Device Lost Page Should Be Open