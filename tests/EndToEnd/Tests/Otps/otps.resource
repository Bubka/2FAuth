*** Settings ***
Library           SeleniumLibrary

*** Variables ***
${OTP OBFUSCATOR SYMBOL}    ●

*** Keywords ***
Otp Should Be Obfuscated
    [Arguments]    ${otp}
    Should Match Regexp    ${otp}    ^[\\s${OTP OBFUSCATOR SYMBOL}]*$

Otp Should Not Be Obfuscated
    [Arguments]    ${otp}
    Should Not Match Regexp    ${otp}    ^[\\s${OTP OBFUSCATOR SYMBOL}]*$

Otp Digits Should Not Be Grouped
    [Arguments]    ${otp}
    Should Match Regexp    ${otp}    ^\\S+$

Otp Digits Should Be Grouped By Pair
    [Arguments]    ${otp}
    Should Match Regexp    ${otp}    ^\\S{2}(\\s{1}\\S{2})+$

Otp Digits Should Be Grouped By Trio
    [Arguments]    ${otp}
    Should Match Regexp    ${otp}    ^\\S{3}(\\s{1}\\S{3})+$

Otp Digits Should Be Grouped By Half
    [Arguments]    ${otp}
    Should Match Regexp    ${otp}    ^\\S+(\\s{1}\\S+)$

