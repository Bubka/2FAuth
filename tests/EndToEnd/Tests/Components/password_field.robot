*** Settings ***
Documentation     A test suite containing tests related to password field component.
Suite Setup       run Keywords
...                   Open Custom Browser
...                   AND    Go To Register Page
Suite Teardown    Close All Browsers
Resource          ../../Pages/register_page.robot

*** Variables ***
${VALIDATION CSS CLASS}    is-dot

*** Test Cases ***
Password Field Min Length Is Validated
    Input Text    ${PASSWORD FIELD}    longEnough
    Validation Mark Is On    valPwdIsLongEnough

Password Field Min Length Is Not Validated
    Input Text    ${PASSWORD FIELD}    short
    Validation Mark Is Off    valPwdIsLongEnough

Password Field Has Lower Case Is Validated
    Input Text    ${PASSWORD FIELD}    lowercase
    Validation Mark Is On    valPwdHasLowerCase

Password Field Has Lower Case Is Not Validated
    Input Text    ${PASSWORD FIELD}    NOTLOWERCASE
    Validation Mark Is Off    valPwdHasLowerCase

Password Field Has Upper Case Is Validated
    Input Text    ${PASSWORD FIELD}    UPPERCASE
    Validation Mark Is On    valPwdHasUpperCase

Password Field Has Upper Case Is Not Validated
    Input Text    ${PASSWORD FIELD}    lowercase
    Validation Mark Is Off    valPwdHasUpperCase

Password Field Has Special Char Is Validated
    Input Text    ${PASSWORD FIELD}    $p€ci@1ch@r
    Validation Mark Is On    valPwdHasSpecialChar

Password Field Has Special Char Is Not Validated
    Input Text    ${PASSWORD FIELD}    nospecialchar
    Validation Mark Is Off    valPwdHasSpecialChar

Password Field Has Number Is Validated
    Input Text    ${PASSWORD FIELD}    p4ssw0rd
    Validation Mark Is On    valPwdHasNumber

Password Field Has Number Is Not Validated
    Input Text    ${PASSWORD FIELD}    password
    Validation Mark Is Off    valPwdHasNumber

Password Field Validates All Rules
    Input Text    ${PASSWORD FIELD}    Pa$$w0rD
    Validation Mark Is On    valPwdIsLongEnough
    Validation Mark Is On    valPwdHasLowerCase
    Validation Mark Is On    valPwdHasUpperCase
    Validation Mark Is On    valPwdHasSpecialChar
    Validation Mark Is On    valPwdHasNumber

Password Should Not Be Readable
    Input Text    ${PASSWORD FIELD}    Pa$$w0rD
    Element Attribute Value Should Be    ${PASSWORD FIELD}    type    password

Password Should Be Readable
    Input Text    ${PASSWORD FIELD}    Pa$$w0rD
    Click Element    ${TOGGLE PASSWORD VISIBILITY BUTTON}
    Element Attribute Value Should Be    ${PASSWORD FIELD}    type    text


*** Keywords ***
Validation Mark Is On
    [Arguments]    ${element}
    Element Should Have Class    ${element}    ${VALIDATION CSS CLASS}

Validation Mark Is Off
    [Arguments]    ${element}
    Element Should Not Have Class    ${element}    ${VALIDATION CSS CLASS}
