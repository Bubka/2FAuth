*** Settings ***
Documentation     A page object to use in 2FA accounts creation tests.
...
Library           SeleniumLibrary
Resource          ../common.resource

*** Variables ***
${CREATE ACCOUNT PAGE URL}    ${ROOT URL}/account/create

${SERVICE FIELD}    txtService
${ACCOUNT FIELD}    txtAccount
${SECRET FIELD}    txtSecret
${PERIOD FIELD}    txtPeriod
${COUNTER FIELD}    txtCounter
${TOTP TOGGLE BUTTON}    btnOtp_typetotp
${HOTP TOGGLE BUTTON}    btnOtp_typehotp
${STEAM TOGGLE BUTTON}    btnOtp_typesteamtotp
${DIGITS BUTTON PREFIX}    btnDigits
${6 DIGITS BUTTON}    btnDigits6
${7 DIGITS BUTTON}    btnDigits7
${8 DIGITS BUTTON}    btnDigits8
${9 DIGITS BUTTON}    btnDigits9
${10 DIGITS BUTTON}    btnDigits10
${ALGORITHM BUTTON PREFIX}    btnAlgorithm
${SHA1}    sha1
${SHA256}    sha256
${SHA512}    sha512
${SMD5}    md5
${CREATE BUTTON}    btnCreate
${PREVIEW BUTTON}    btnPreview

*** Keywords ***
Create TwoFAccount Page Should Be Open
    Location Should Be    ${CREATE ACCOUNT PAGE URL}

Go To Create TwoFAccount Page
    Go Authenticated To    ${CREATE ACCOUNT PAGE URL}
    Set Footer As Static

Create TOTP TwoFAccount
    [Arguments]    ${service}    ${account}    ${secret}=XXXXXXXX    ${digits}=6    ${algorithm}=${SHA1}    ${period}=30
    Go To Create TwoFAccount Page
    Select TOTP Type
    Fill Period Field    ${period}
    Create OTP TwoFAccount    ${service}    ${account}    ${secret}    ${digits}    ${algorithm}
    &{otp data} =    Create Dictionary    type=TOTP    service=${service}    account=${account}    secret=${secret}    digits=${digits}    algorithm=${algorithm}    period=${period}
    RETURN    &{otp data}

Create HOTP TwoFAccount
    [Arguments]    ${service}    ${account}    ${secret}=XXXXXXXX    ${digits}=6    ${algorithm}=${SHA1}    ${counter}=1
    Go To Create TwoFAccount Page
    Select HOTP Type
    Fill Counter Field    ${counter}
    Create OTP TwoFAccount    ${service}    ${account}    ${secret}    ${digits}    ${algorithm}
    &{otp data} =    Create Dictionary    type=HOTP    service=${service}    account=${account}    secret=${secret}    digits=${digits}    algorithm=${algorithm}    period=${counter}
    RETURN    &{otp data}

Create OTP TwoFAccount
    [Arguments]    ${service}    ${account}    ${secret}=XXXXXXXX    ${digits}=6    ${algorithm}=${SHA1}
    Fill Service Field    ${service}
    Fill Account Field    ${account}
    Fill Secret Field    ${secret}
    Set Digits To    ${digits}
    Set Algorithm To    ${algorithm}
    Click Element    ${CREATE BUTTON}
    Wait Until Location Is    ${ACCOUNTS PAGE URL}    10s

*** Keywords ***
Fill Service Field
    [Arguments]    ${service}
    Input Text    ${SERVICE FIELD}    ${service}
    
Fill Account Field
    [Arguments]    ${account}
    Input Text    ${ACCOUNT FIELD}    ${account}
    
Select TOTP Type
    Click Element    ${TOTP TOGGLE BUTTON}
    
Select HOTP Type
    Click Element    ${HOTP TOGGLE BUTTON}
    
Select STEAM Type
    Click Element    ${STEAM TOGGLE BUTTON}
    
Fill Secret Field
    [Arguments]    ${secret}
    Input Text    ${SECRET FIELD}    ${secret}
    
Fill Period Field
    Wait Until Page Contains Element    ${PERIOD FIELD}
    [Arguments]    ${period}=30
    Input Text    ${PERIOD FIELD}    ${period}
    
Fill Counter Field
    Wait Until Page Contains Element    ${COUNTER FIELD}
    [Arguments]    ${counter}=1
    Input Text    ${COUNTER FIELD}    ${counter}

Set Digits To
    [Arguments]    ${digits}
    Click Element    ${DIGITS BUTTON PREFIX}${digits}

Set Algorithm To
    [Arguments]    ${algorithm}
    Click Element    ${ALGORITHM BUTTON PREFIX}${algorithm}