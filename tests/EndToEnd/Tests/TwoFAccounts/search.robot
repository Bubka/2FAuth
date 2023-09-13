*** Settings ***
Documentation     A test suite containing tests related to 2FAccounts search.
Suite Setup       Run Keywords
...                   Open Custom Browser
...                   AND    Play Admin Sign In Workflow
Suite Teardown    Close All Browsers
Test Setup        Set Up For Search
Resource          ../../Pages/account_create_page.robot
Resource          ../../Pages/accounts_page.robot
Resource          ../../common.resource
Library           String

*** Variables ***
${CLEAR SEARCH BUTTON}    btnClearSearch

*** Test Cases ***

Search Field Should Be Empty By Default
    Search Field Should Be Empty

Search Field Should Show Close Button When Filled
    Input Text    ${SEARCH FIELD}    lorem ipsum
    Wait Until Page Contains Element    ${CLEAR SEARCH BUTTON}

Clear Search Button Should Clear Search
    Input Text    ${SEARCH FIELD}    lorem ipsum
    Wait Until Page Contains Element    ${CLEAR SEARCH BUTTON}
    Click Element    ${CLEAR SEARCH BUTTON}
    Search Field Should Be Empty

Clearing Search Field Should Hide Clear Search Button
    Input Text    ${SEARCH FIELD}    lorem ipsum
    Wait Until Page Contains Element    ${CLEAR SEARCH BUTTON}
    Press Keys    ${SEARCH FIELD}    CTRL+a
    Press Keys    None    DELETE
    Page Should Not Contain Element    ${CLEAR SEARCH BUTTON}

Search Should Show Exact Match On Service
    ${service} =    Generate Random String    10    [LETTERS]
    ${account} =    Generate Random String    10    [LETTERS]
    &{TOTP} =    Create TOTP TwoFAccount    ${service}    ${account}
    Search Should Show Matching Results    ${TOTP.service}    1    True
    Search Should Show Matching Results    ${TOTP.service}    1    False

Search Should Show Exact Match On Account
    ${service} =    Generate Random String    10    [LETTERS]
    ${account} =    Generate Random String    10    [LETTERS]
    &{TOTP} =    Create TOTP TwoFAccount    ${service}    ${account}
    Search Should Show Matching Results    ${TOTP.account}    1    True
    Search Should Show Matching Results    ${TOTP.account}    1    False

Search Should Show Partial Match On Service
    ${partial_service} =    Generate Random String    10    [LETTERS]
    ${partial_account} =    Generate Random String    10    [LETTERS]
    ${service a} =    Set Variable    xx_${partial_service}__xx
    ${account a} =    Set Variable    xxx_${partial_account}__xxx
    &{TOTP} =    Create TOTP TwoFAccount    ${service a}    ${account a}
    ${service b} =    Set Variable    yy_${partial_service}__yyy
    ${account b} =    Set Variable    yyy_${partial_account}__yyy
    &{TOTP} =    Create TOTP TwoFAccount    ${service b}    ${account b}
    Search Should Show Matching Results    ${partial_service}    2    True
    Search Should Show Matching Results    ${partial_service}    2    False

Search Should Show Partial Match On Account
    ${partial_service} =    Generate Random String    10    [LETTERS]
    ${partial_account} =    Generate Random String    10    [LETTERS]
    ${service a} =    Set Variable    xx_${partial_service}__xx
    ${account a} =    Set Variable    xxx_${partial_account}__xxx
    &{TOTP} =    Create TOTP TwoFAccount    ${service a}    ${account a}
    ${service b} =    Set Variable    yy_${partial_service}__yyy
    ${account b} =    Set Variable    yyy_${partial_account}__yyy
    &{TOTP} =    Create TOTP TwoFAccount    ${service b}    ${account b}
    Search Should Show Matching Results    ${partial_account}    2    True
    Search Should Show Matching Results    ${partial_account}    2    False

*** Keywords ***
Search Field Should Be Empty
    ${search} =    Get Value    ${SEARCH FIELD}
    Should Be Empty    ${search}

Search Should Show Matching Results
    [Arguments]    ${searched value}    ${expected results number}=1    ${ignore_case}=False
    Wait Until Page Contains Element    ${SEARCH FIELD}
    Input Text    ${SEARCH FIELD}    ${searched value}
    Search Results Number Should Equal    ${expected results number}
    @{twofaccounts} =    Get Visible TwoFAccounts Elements
    FOR    ${twofaccount}    IN    @{twofaccounts}
        Element Should Contain    ${twofaccount}    ${searched value}    None    ${ignore_case}
    END

Search Results Number Should Equal
    [Arguments]    ${expected results number}
    ${result count} =    Get Element Count    ${2FA ACCOUNT}
    Should Be Equal As Integers    ${result count}    ${expected results number}

Set Up For Search
    Go To Accounts Page
    ${twofaccount number} =    Get Element Count    ${2FA ACCOUNT}
    IF    ${twofaccount number} == 0
        Create TOTP TwoFAccount    lorem    ipsum
    END
    Wait Until Page Contains Element    ${SEARCH FIELD}