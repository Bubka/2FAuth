*** Settings ***
Documentation     A test suite containing tests related to OTP generation and display.
Suite Setup       Run Keywords
...                   Open Custom Browser
...                   AND    Play Admin Sign In Workflow
...                   AND    Run Set Option Keyword And Come Back    Set Option Show Password To On Demand
Suite Teardown    Close All Browsers
Library           String
Resource          ../../Pages/accounts_page.robot
Resource          ../../Pages/settings_options_page.robot
Resource          ../../common.resource
Resource          otps.resource

*** Test Cases ***
Readable Otp Should Be Displayed
    Run Set Option Keyword And Come Back    Disable Option Show Otp As Dot
    Show An Otp In Modal
    ${otp value}=    Get OTP Value Shown In Modal
    Otp Should Not Be Obfuscated    ${otp value}

Obfuscated Otp Should Be Displayed
    Run Set Option Keyword And Come Back    Enable Option Show Otp As Dot
    Show An Otp In Modal
    ${otp value}=    Get OTP Value Shown In Modal
    Otp Should Be Obfuscated    ${otp value}

Otp Digits Should Not Be Grouped
    Run Set Option Keyword And Come Back    Disable Option Password Formatting
    Show An Otp In Modal
    ${otp value}=    Get OTP Value Shown In Modal
    Otp Digits Should Not Be Grouped    ${otp value}

Otp Digits Should Be Grouped By Pair
    @{set option keywords} =    Create List
    ...    Enable Option Password Formatting
    ...    Set Option Password Formatting By Pair
    Run Multiple Set Option Keyword And Come Back    @{set option keywords}
    Show An Otp In Modal
    ${otp value}=    Get OTP Value Shown In Modal
    Otp Digits Should Be Grouped By Pair    ${otp value}

Otp Digits Should Be Grouped By Trio
    @{set option keywords} =    Create List
    ...    Enable Option Password Formatting
    ...    Set Option Password Formatting By Trio
    Run Multiple Set Option Keyword And Come Back    @{set option keywords}
    Show An Otp In Modal
    ${otp value}=    Get OTP Value Shown In Modal
    Otp Digits Should Be Grouped By Trio    ${otp value}

Otp Digits Should Be Grouped By Half
    @{set option keywords} =    Create List
    ...    Enable Option Password Formatting
    ...    Set Option Password Formatting By Half
    Run Multiple Set Option Keyword And Come Back    @{set option keywords}
    Show An Otp In Modal
    ${otp value}=    Get OTP Value Shown In Modal
    Otp Digits Should Be Grouped By Half    ${otp value}

Modal Should Be Closed After Otp Copy
    Run Set Option Keyword And Come Back    Enable Option Close Otp After Copy
    Show An Otp In Modal
    Click Otp In Modal To Copy It
    A Success Notification Should Appear
    Wait Until Element Is Not Visible    ${OTP}

Modal Should Not Be Closed After Otp Copy
    Run Set Option Keyword And Come Back    Disable Option Close Otp After Copy
    Show An Otp In Modal
    Click Otp In Modal To Copy It
    A Success Notification Should Appear
    Element Should Be Visible    ${OTP}

Readable Otp Should Be Copied On Click
    @{set option keywords} =    Create List
    ...    Disable Option Show Otp As Dot
    ...    Enable Option Password Formatting
    ...    Disable Option Copy Otp On Display
    Run Multiple Set Option Keyword And Come Back    @{set option keywords}
    Show An Otp In Modal
    Click Otp In Modal To Copy It
    A Success Notification Should Appear
    On Screen Otp Notified As Copied Should Be In Clipboard

Obfuscated Otp Should Be Copied On Click
    ${random value} =    Generate Random String    8    [LETTERS]
    Copy To Clipboard    ${random value}
    @{set option keywords} =    Create List
    ...    Enable Option Show Otp As Dot
    ...    Enable Option Password Formatting
    ...    Enable Option Copy Otp On Display
    Run Multiple Set Option Keyword And Come Back    @{set option keywords}
    Show An Otp In Modal
    Click Otp In Modal To Copy It
    A Success Notification Should Appear
    Close Modal Otp
    Wait Until Element Is Visible    ${SEARCH FIELD}
    Press Keys    ${SEARCH FIELD}    CTRL+v
    ${clipboard} =    Get Value    ${SEARCH FIELD}
    Should Match Regexp    ${clipboard}    ^\\d+$

Readable Otp Should Be Copied On Display
    @{set option keywords} =    Create List
    ...    Disable Option Show Otp As Dot
    ...    Enable Option Password Formatting
    ...    Enable Option Copy Otp On Display
    Run Multiple Set Option Keyword And Come Back    @{set option keywords}
    Show An Otp In Modal
    A Success Notification Should Appear
    On Screen Otp Notified As Copied Should Be In Clipboard

Obfuscated Otp Should Be Copied On Display
    ${random value} =    Generate Random String    8    [LETTERS]
    Copy To Clipboard    ${random value}
    @{set option keywords} =    Create List
    ...    Enable Option Show Otp As Dot
    ...    Enable Option Password Formatting
    ...    Enable Option Copy Otp On Display
    Run Multiple Set Option Keyword And Come Back    @{set option keywords}
    Show An Otp In Modal
    A Success Notification Should Appear
    Close Modal Otp
    Wait Until Element Is Visible    ${SEARCH FIELD}
    Press Keys    ${SEARCH FIELD}    CTRL+v
    ${clipboard} =    Get Value    ${SEARCH FIELD}
    Should Match Regexp    ${clipboard}    ^\\d+$

Otp Should Not Be Copied On Display
    ${random value} =    Generate Random String
    Copy To Clipboard    ${random value}
    Run Set Option Keyword And Come Back    Disable Option Copy Otp On Display
    Show An Otp In Modal
    Clipboard Should Contain    ${random value}

*** Keywords ***

On Screen Otp Notified As Copied Should Be In Clipboard
    ${displayed otp}=    Get OTP Value Shown In Modal
    ${otp} =    Replace String    ${displayed otp}    ${SPACE}    ${EMPTY}
    Clipboard Should Contain    ${otp}