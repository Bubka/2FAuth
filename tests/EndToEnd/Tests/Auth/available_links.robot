*** Settings ***
Documentation     A test suite containing tests related to the login page links.
Suite Setup       Open Browser To Legacy Login Page
Suite Teardown    Close Browser
Resource          ../../Pages/login_page.robot

*** Test Cases ***
Reset Link
    Element Should Be Visible    ${RESET PASSWORD LINK}

Webauthn login Link
    Element Should Be Visible    ${SIGN IN WITH WEBAUTHN LINK}

Legacy login Link
    Show Webauthn Form
    Element Should Be Visible    ${SIGN IN WITH LOGIN PASSWORD LINK}

Recover Account Link
    Show Webauthn Form
    Element Should Be Visible    ${RECOVER YOUR ACCOUNT LINK}