*** Settings ***
Documentation     A test suite containing tests related to browsing redirections.
Suite Setup       Run Keywords
...                   Open Custom Browser
...                   AND    Delete All Cookies
...                   AND    Play Logout Workflow
Suite Teardown    Close All Browsers
Resource          ../../Pages/account_create_page.robot
Resource          ../../Pages/account_edit_page.robot
Resource          ../../Pages/account_import_page.robot
Resource          ../../Pages/account_show_qrcode_page.robot
Resource          ../../Pages/accounts_page.robot
Resource          ../../Pages/capture_page.robot
Resource          ../../Pages/group_create_page.robot
Resource          ../../Pages/group_edit_page.robot
Resource          ../../Pages/groups_page.robot
Resource          ../../Pages/settings_account_page.robot
Resource          ../../Pages/settings_oauth_page.robot
Resource          ../../Pages/settings_oauth_pat_create_page.robot
Resource          ../../Pages/settings_options_page.robot
Resource          ../../Pages/settings_webauthn_page.robot
Resource          ../../Pages/settings_webauthn_edit_page.robot
Resource          ../../Pages/start_page.robot
Resource          ../../common.resource

*** Test Cases ***
Protected Page
    [Template]    Anonymous Should Be Redirected To Login Page
    ${CREATE ACCOUNT PAGE URL}
    ${EDIT ACCOUNT PAGE URL}
    ${IMPORT ACCOUNTS PAGE URL}
    ${SHOW ACCOUNT QRCODE PAGE URL}
    ${ACCOUNTS PAGE URL}
    ${CAPTURE PAGE URL}
    ${CREATE GROUP PAGE URL}
    ${EDIT GROUP PAGE URL}
    ${GROUPS PAGE URL}
    ${ACCOUNT SETTINGS PAGE URL}
    ${OAUTH SETTINGS PAGE URL}
    ${CREATE OAUTH PAT SETTINGS PAGE URL}
    ${OPTIONS SETTINGS PAGE URL}
    ${WEBAUTHN SETTINGS PAGE URL}
    ${RENAME WEBAUTHN DEVICE SETTINGS PAGE URL}
    ${START PAGE URL}
    
*** Keywords ***
Anonymous Should Be Redirected To Login Page
    [Arguments]    ${url}
    Go To    ${url}
    Wait Until Location Is    ${LOGIN PAGE URL}