*** Settings ***
Documentation     A test suite containing tests related to the about page static content.
Suite Setup       Run Keywords
...                   Open Custom Browser
...                   AND    Play Logout Workflow
...                   AND    Go To About Page
...                   AND    Set Footer As Static
Suite Teardown    Close All Browsers
Resource          ../../Pages/about_page.robot
Resource          ../../common.resource

*** Test Cases ***
Environment Vars Should Be Visible To Anonymous
    Environment Vars Should Be Visible

Environment Vars Should Have A Value
    Block Vars Should Have A Value    listInfos

Copying Env Vars Should Notify On Success
    Copying Block Vars Should Notify On Success    btnCopyEnvVars

User Preferences Should Not Be Visible To Anonymous
    User Preferences Should Not Be Visible

Admin Settings Should Not Be Visible To Anonymous
    Admin Settings Should Not Be Visible