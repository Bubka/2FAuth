*** Settings ***
Documentation     A test suite containing tests related to password request.
Suite Setup       run Keywords
...                   Open Custom Browser
...                   AND    Play Logout Workflow
Test Setup        Go To Password Request Page
Suite Teardown    Close All Browsers
Resource          ../../Pages/password_request_page.robot
Resource          authentication.resource

*** Test Cases ***
Invalid Email Is Rejected
    [Template]    Email Submit Should Fail
    ${EMPTY}
    ${UNREGISTERED EMAIL}
    ${INVALID EMAIL MALFORMED}

Password Request Is Submitted Sucessfully
    Submit Data To Password Request Form    ${ADMIN EMAIL}
    Wait Until Element Is Visible    css:#vueNotification .is-success    30s
    Password Request Page Should Be Open

New Request While Pending One Is Rejected
    Email Submit Should Fail    ${ADMIN EMAIL}

Request Form Can Be Quit
    Click Link    ${CANCEL BUTTON}
    Login Page Should Be Open
    
*** Keywords ***
Email Submit Should Fail
    [Arguments]    ${bad email}
    Submit Data To Password Request Form     ${bad email}
    password_request_page.Email Field Should Show An Error
    Password Request Page Should Be Open