*** Settings ***
Documentation     A test suite containing tests related to the about page static content.
Suite Setup       Run Keywords
...                   Open Custom Browser
...                   AND    Play Admin Sign In Workflow
...                   AND    Go To About Page
...                   AND    Set Footer As Static
Suite Teardown    Close All Browsers
Resource          ../../Pages/about_page.robot
Resource          ../../common.resource

*** Test Cases ***
Environment Vars Should Be Visible To Admin
    Environment Vars Should Be Visible

User Preferences Should Be Visible To Admin
    User Preferences Should Be Visible

Admin Settings Should Be Visible To Admin
    Admin Settings Should Be Visible

Admin Settings Should Have A Value
    Block Vars Should Have A Value    listAdminSettings

Copying Admin Settings Should Notify On Success
    Copying Block Vars Should Notify On Success    btnCopyAdminSettings