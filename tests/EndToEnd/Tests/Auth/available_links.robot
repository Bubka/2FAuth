*** Settings ***
Documentation     A test suite to check available links on the login page.
Suite Setup       Available Links Suite Setup
Suite Teardown    Close All Browsers
Resource          ../../Pages/login_page.robot

*** Test Cases ***
Reset Link Is Visible
    Element Should Be Visible    ${RESET PASSWORD LINK}

Webauthn login Link Is Visible
    Element Should Be Visible    ${SIGN IN WITH WEBAUTHN LINK}

Legacy login Link Is Visible
    Show Webauthn Form
    Webauthn Form Should Be Visible
    Element Should Be Visible    ${SIGN IN WITH LOGIN PASSWORD LINK}

Recover Account Link Is Visible
    Show Webauthn Form
    Webauthn Form Should Be Visible
    Element Should Be Visible    ${RECOVER YOUR ACCOUNT LINK}

*** Keywords ***
Available Links Suite Setup
    Open Blank Browser
    Go To Legacy Login Page
    Legacy Form Should Be Visible