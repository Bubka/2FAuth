*** Settings ***
Documentation     A test suite to check Close & Back buttons targeted urls.
Suite Setup       Run keywords
...                   Open Custom Browser
...                   AND    Play Admin Sign In Workflow
Suite Teardown    Close All Browsers
Resource          ../../Pages/login_page.robot
Resource          ../../Pages/about_page.robot
Resource          ../../Pages/accounts_page.robot
Resource          ../../Pages/groups_page.robot
Resource          ../../Pages/settings_options_page.robot
Resource          ../../Pages/settings_webauthn_page.robot
Resource          ../../Pages/start_page.robot
Resource          ../../Pages/account_import_page.robot
Resource          ../../common.resource

*** Variables ***
${STARTING URL}    ${OPTIONS SETTINGS PAGE URL}

*** Test Cases ***
Back Button From About Page Should Send To Previous Page
    Go To    ${STARTING URL}
    Click Link    ${ABOUT LINK}
    Click Link    ${BACK BUTTON}
    Wait Until Location Is    ${STARTING URL}

Back Button From About Page Without History Should Send To Accounts Page
    Go To    ${STARTING URL}
    Go To About Page
    Click Link    ${BACK BUTTON}
    Wait Until Location Is    ${ACCOUNTS PAGE URL}

Back Button From Start Page Should Send To Accounts Page
    Go To Start Page
    Click Link    ${BACK BUTTON}
    Wait Until Location Is    ${ACCOUNTS PAGE URL}

Back Button From Import Page Should Send To Accounts Page
    Go To Start Page
    Choose To Import Accounts
    Exit Import Page
    Wait Until Location Is    ${ACCOUNTS PAGE URL}

Cancel Button From Import Page Should Send To Accounts Page
    Go To Start Page
    Choose To Import Accounts
    Cancel Import
    Wait Until Location Is    ${ACCOUNTS PAGE URL}

Close Button Should Close Modal Window
    Go To    ${ACCOUNTS PAGE URL}
    Show First Totp In Modal
    Click Element    ${CLOSE BUTTON}
    Wait Until Element Is Not Visible    class:modal-otp

Close Button Should Close Group Switch
    Go To    ${ACCOUNTS PAGE URL}
    Show Group Switch
    Wait Until Page Contains Element    ${CLOSE BUTTON}
    Click Element    ${CLOSE BUTTON}
    Wait Until Page Does Not Contain Element    ${GROUP SWITCH}

Close Button From Groups Page Should Send To Accounts Page
    Go To    ${GROUPS PAGE URL}
    Wait Until Page Contains Element    ${CLOSE BUTTON}
    Click Element    ${CLOSE BUTTON}
    Wait Until Location Is    ${ACCOUNTS PAGE URL}

Close Button From Settings Should Send To Page Before Settings Browsing
    [Template]    Close Button From A Settings Tab Should Send To Previous Page
    ${OPTIONS TAB}
    ${WEBAUTHN TAB}
    ${ACCOUNT TAB}
    ${OAUTH TAB}

Visiting About Page Via Settings Pages Should End To Starting Page
    Go To Groups Page
    Browse To Settings
    Activate Webauthn Settings Tab
    Browse To About Page
    Exit About Page
    Webauthn Settings Page Should Be Open
    Exit Settings
    Groups Page Should Be Open


*** Keywords ***
Close Button From A Settings Tab Should Send To Previous Page
    [Arguments]    ${tab}
    Go To Groups Page
    Browse To Settings
    Activate Settings Tab    ${tab}
    Exit Settings
    Groups Page Should Be Open