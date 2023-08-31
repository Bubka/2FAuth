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

*** Test Cases ***
Readable Otp Should Be Displayed
    Run Set Option Keyword And Come Back    Disable Option Show Otp As Dot
    Show An Otp In Modal
    ${otp value}=    Get OTP Value Shown In Modal
    Should Not Match Regexp    ${otp value}    ^[\\s${OTP OBFUSCATOR SYMBOL}]*$

Obfuscated Otp Should Be Displayed
    Run Set Option Keyword And Come Back    Enable Option Show Otp As Dot
    Show An Otp In Modal
    ${otp value}=    Get OTP Value Shown In Modal
    Should Match Regexp    ${otp value}    ^[\\s${OTP OBFUSCATOR SYMBOL}]*$

Otp Digits Should Not Be Grouped
    Run Set Option Keyword And Come Back    Disable Option Password Formatting
    Show An Otp In Modal
    ${otp value}=    Get OTP Value Shown In Modal
    Should Match Regexp    ${otp value}    ^\\S+$

Otp Digits Should Be Grouped By Pair
    Run Set Option Keyword And Come Back    Enable Option Password Formatting
    Run Set Option Keyword And Come Back    Set Option Password Formatting By Pair
    Show An Otp In Modal
    ${otp value}=    Get OTP Value Shown In Modal
    Should Match Regexp    ${otp value}    ^\\S{2}(\\s{1}\\S{2})+$

Otp Digits Should Be Grouped By Trio
    Run Set Option Keyword And Come Back    Enable Option Password Formatting
    Run Set Option Keyword And Come Back    Set Option Password Formatting By Trio
    Show An Otp In Modal
    ${otp value}=    Get OTP Value Shown In Modal
    Should Match Regexp    ${otp value}    ^\\S{3}(\\s{1}\\S{3})+$

Otp Digits Should Be Grouped By Half
    Run Set Option Keyword And Come Back    Enable Option Password Formatting
    Run Set Option Keyword And Come Back    Set Option Password Formatting By Half
    Show An Otp In Modal
    ${otp value}=    Get OTP Value Shown In Modal
    Should Match Regexp    ${otp value}    ^\\S+(\\s{1}\\S+)$

Modal Should Be Closed After Otp Copy
    Run Set Option Keyword And Come Back    Enable Option Close Otp After Copy
    Show An Otp In Modal
    Click Otp To Copy It
    Wait Until Element Is Not Visible    ${OTP}

Modal Should Not Be Closed After Otp Copy
    Run Set Option Keyword And Come Back    Disable Option Close Otp After Copy
    Show An Otp In Modal
    Click Otp To Copy It
    Element Should Be Visible    ${OTP}

Readable Otp Should Be Copied On Click
    Run Set Option Keyword And Come Back    Disable Option Show Otp As Dot
    Run Set Option Keyword And Come Back    Enable Option Password Formatting
    Run Set Option Keyword And Come Back    Disable Option Copy Otp On Display
    Show An Otp In Modal
    Click Otp To Copy It
    A Success Notification Should Appear
    On Screen Otp Notified As Copied Should Be In Clipboard

Obfuscated Otp Should Be Copied On Click
    ${random value} =    Generate Random String    8    [LETTERS]
    Copy To Clipboard    ${random value}
    Run Set Option Keyword And Come Back    Enable Option Show Otp As Dot
    Run Set Option Keyword And Come Back    Enable Option Password Formatting
    Run Set Option Keyword And Come Back    Enable Option Copy Otp On Display
    Show An Otp In Modal
    Click Otp To Copy It
    A Success Notification Should Appear
    Close Modal Otp
    Wait Until Element Is Visible    ${SEARCH FIELD}
    Press Keys    ${SEARCH FIELD}    CTRL+v
    ${clipboard} =    Get Value    ${SEARCH FIELD}
    Should Match Regexp    ${clipboard}    ^\\d+$

Readable Otp Should Be Copied On Display
    Run Set Option Keyword And Come Back    Disable Option Show Otp As Dot
    Run Set Option Keyword And Come Back    Enable Option Password Formatting
    Run Set Option Keyword And Come Back    Enable Option Copy Otp On Display
    Show An Otp In Modal
    A Success Notification Should Appear
    On Screen Otp Notified As Copied Should Be In Clipboard

Obfuscated Otp Should Be Copied On Display
    ${random value} =    Generate Random String    8    [LETTERS]
    Copy To Clipboard    ${random value}
    Run Set Option Keyword And Come Back    Enable Option Show Otp As Dot
    Run Set Option Keyword And Come Back    Enable Option Password Formatting
    Run Set Option Keyword And Come Back    Enable Option Copy Otp On Display
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