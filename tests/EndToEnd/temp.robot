*** Settings ***
Documentation     A test suite to check available links on the login page.
Suite Setup       Open Blank Browser
# Suite Teardown    Close Browser
Resource          Pages/login_page.robot
Resource          workflows.resource

*** Variables ***
${HOME PAGE URL}    http://${SERVER}/

*** Test Cases ***
My Test
    Play Delete User Account Workflow
