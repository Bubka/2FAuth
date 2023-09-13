*** Settings ***
Documentation     A test suite containing tests related to 2FAccounts display.
Suite Setup       Run Keywords
...                   Open Custom Browser
...                   AND    Play Admin Sign In Workflow
Suite Teardown    Close All Browsers
Resource          ../../Pages/accounts_page.robot
Resource          ../../Pages/settings_options_page.robot
Resource          ../../common.resource

*** Variables ***

*** Test Cases ***
Grid Mode Should Display TwoFAccounts In Grid
    Run Set Option Keyword And Come Back    Set Option Display Mode To Grid
    @{twofaccounts} =    Get Visible TwoFAccounts Elements
    FOR    ${twofaccount}    IN    @{twofaccounts}
        Element Should Have Class    ${twofaccount}    ${GRID CLASS}
    END

List Mode Should Display TwoFAccounts In List
    Run Set Option Keyword And Come Back    Set Option Display Mode To Grid
    @{twofaccounts} =    Get Visible TwoFAccounts Elements
    FOR    ${twofaccount}    IN    @{twofaccounts}
        Element Should Have Class    ${twofaccount}    ${LIST CLASS}
    END

Icons Should Be Visible
    Run Set Option Keyword And Come Back    Enable Option Show Icons
    Wait Until Accounts Are Loaded
    Page Should Contain Image    class:tfa-icon

Icons Should Not Be Visible
    Run Set Option Keyword And Come Back    Disable Option Show Icons
    Wait Until Accounts Are Loaded
    Page Should Not Contain Image    class:tfa-icon
