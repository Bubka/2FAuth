*** Settings ***
Documentation     A test suite containing tests related to OTP generation and display.
Suite Setup       Run Keywords
...                   Open Custom Browser
...                   AND    Play Admin Sign In Workflow
...                   AND    Run Set Option Keyword And Come Back    Set Option Show Password To Constantly
Suite Teardown    Close All Browsers
Library           String
Resource          ../../Pages/accounts_page.robot
Resource          ../../Pages/settings_options_page.robot
Resource          ../../common.resource
Resource          otps.resource

*** Test Cases ***
Readable Otps Should Be Displayed On Home
    Run Set Option Keyword And Come Back    Disable Option Show Otp As Dot
    @{otps} =    Get OTP Values Shown On Home
    FOR    ${otp}    IN    @{otps}
        Otp Should Not Be Obfuscated    ${otp}
    END

Obfuscated Otps Should Be Displayed On Home
    Run Set Option Keyword And Come Back    Enable Option Show Otp As Dot
    @{otps} =    Get OTP Values Shown On Home
    FOR    ${otp}    IN    @{otps}
        Otp Should Be Obfuscated    ${otp}
    END

All Otp Digits Should Not Be Grouped
    Run Set Option Keyword And Come Back    Disable Option Password Formatting
    @{otps} =    Get OTP Values Shown On Home
    FOR    ${otp}    IN    @{otps}
        Otp Digits Should Not Be Grouped    ${otp}
    END

All Otp Digits Should Be Grouped By Pair
    Run Set Option Keyword And Come Back    Enable Option Password Formatting
    Run Set Option Keyword And Come Back    Set Option Password Formatting By Pair
    @{otps} =    Get OTP Values Shown On Home
    FOR    ${otp}    IN    @{otps}
        Otp Digits Should Be Grouped By Pair    ${otp}
    END

All Otp Digits Should Be Grouped By Trio
    Run Set Option Keyword And Come Back    Enable Option Password Formatting
    Run Set Option Keyword And Come Back    Set Option Password Formatting By Trio
    @{otps} =    Get OTP Values Shown On Home
    FOR    ${otp}    IN    @{otps}
        Otp Digits Should Be Grouped By Trio    ${otp}
    END

All Otp Digits Should Be Grouped By Half
    Run Set Option Keyword And Come Back    Enable Option Password Formatting
    Run Set Option Keyword And Come Back    Set Option Password Formatting By Half
    @{otps} =    Get OTP Values Shown On Home
    FOR    ${otp}    IN    @{otps}
        Otp Digits Should Be Grouped By Half    ${otp}
    END

Readable Otp Should Be Copied On Click
    Run Set Option Keyword And Come Back    Disable Option Show Otp As Dot
    Run Set Option Keyword And Come Back    Enable Option Password Formatting
    Click Otp On Home To Copy It
    A Success Notification Should Appear
    Otp Notified As Copied Should Be In Clipboard


*** Keywords ***

Otp Notified As Copied Should Be In Clipboard
    ${clicked otp} =    Get Text    ${ALWAYS ON OTP}:first-child
    ${otp} =    Replace String    ${clicked otp}    ${SPACE}    ${EMPTY}
    Clipboard Should Contain    ${otp}