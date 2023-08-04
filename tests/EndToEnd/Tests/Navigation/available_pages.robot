*** Settings ***
Documentation     A test suite containing tests related to browsing pages.
Suite Setup       Run Keywords
...                   Open Custom Browser
...                   AND    Play Admin Sign In Workflow
Suite Teardown    Close All Browsers
Resource          ../../Pages/404_page.robot
Resource          ../../Pages/about_page.robot
Resource          ../../Pages/account_create_page.robot
Resource          ../../Pages/account_edit_page.robot
Resource          ../../Pages/account_import_page.robot
Resource          ../../Pages/account_show_qrcode_page.robot
Resource          ../../Pages/accounts_page.robot
Resource          ../../Pages/autolock_page.robot
Resource          ../../Pages/capture_page.robot
Resource          ../../Pages/error_page.robot
Resource          ../../Pages/group_create_page.robot
Resource          ../../Pages/group_edit_page.robot
Resource          ../../Pages/groups_page.robot
Resource          ../../Pages/login_page.robot
Resource          ../../Pages/password_request_page.robot
Resource          ../../Pages/password_reset_page.robot
Resource          ../../Pages/register_page.robot
Resource          ../../Pages/settings_account_page.robot
Resource          ../../Pages/settings_oauth_page.robot
Resource          ../../Pages/settings_oauth_pat_create_page.robot
Resource          ../../Pages/settings_options_page.robot
Resource          ../../Pages/settings_webauthn_page.robot
Resource          ../../Pages/settings_webauthn_edit_page.robot
Resource          ../../Pages/start_page.robot
Resource          ../../Pages/webauthn_lost_page.robot
Resource          ../../Pages/webauthn_recover_page.robot
Resource          ../../common.resource

*** Test Cases ***
Page exists
    [Template]    Page Should Be Reachable
    ${ABOUT PAGE URL}
    ${CREATE ACCOUNT PAGE URL}
    ${EDIT ACCOUNT PAGE URL}
    ${IMPORT ACCOUNTS PAGE URL}
    ${SHOW ACCOUNT QRCODE PAGE URL}
    ${ACCOUNTS PAGE URL}
    ${CAPTURE PAGE URL}
    ${CREATE GROUP PAGE URL}
    ${EDIT GROUP PAGE URL}
    ${GROUPS PAGE URL}
    ${LOGIN PAGE URL}
    ${PASSWORD REQUEST PAGE URL}
    ${PASSWORD RESET PAGE URL}
    ${REGISTER PAGE URL}
    ${ACCOUNT SETTINGS PAGE URL}
    ${OAUTH SETTINGS PAGE URL}
    ${CREATE OAUTH PAT SETTINGS PAGE URL}
    ${OPTIONS SETTINGS PAGE URL}
    ${WEBAUTHN SETTINGS PAGE URL}
    ${RENAME WEBAUTHN DEVICE SETTINGS PAGE URL}
    ${START PAGE URL}
    ${WEBAUTHN LOST PAGE URL}
    ${WEBAUTHN RECOVER PAGE URL}
    # autolock page must be test lastly because it logs the user out automatically
    ${AUTOLOCK PAGE URL}

Unknown Page
    Go To    ${ROOT URL}/_UNKNOWN_PAGE_
    Wait Until Location Is    ${404 PAGE URL}

*** Keywords ***
Page Should Be Reachable
    [Arguments]    ${url}
    Go To    ${url}
    Wait Until Location Is    ${url}