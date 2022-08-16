*** Settings ***
Documentation     A test suite containing tests related to browsing redirections.
Suite Setup       Open Blank Browser
Suite Teardown    Close All Browsers
Resource          ../../Pages/register_page.robot
Resource          ../../Pages/accounts_page.robot
Resource          ../../Pages/settings_account_page.robot
Resource          ../../Pages/settings_oauth_page.robot
Resource          ../../Pages/settings_options_page.robot
Resource          ../../Pages/settings_webauthn_page.robot
Resource          ../../Pages/start_page.robot
Resource          ../../Pages/create_account_page.robot
Resource          ../../Pages/edit_account_page.robot
Resource          ../../common.resource

*** Test Cases ***
Protected Page
    [Template]    Anonymous Should Be Redirected To Login Page
    ${ACCOUNTS PAGE URL}
    ${ACCOUNT SETTINGS PAGE URL}
    ${OAUTH SETTINGS PAGE URL}
    ${OPTIONS SETTINGS PAGE URL}
    ${WEBAUTHN SETTINGS PAGE URL}
    ${CREATE ACCOUNT PAGE URL}
    ${EDIT ACCOUNT PAGE URL}
    ${START PAGE URL}
    
*** Keywords ***
Anonymous Should Be Redirected To Login Page
    [Arguments]    ${url}
    Delete All Cookies
    Go To    ${url}
    Location Should Be    ${LOGIN PAGE URL}