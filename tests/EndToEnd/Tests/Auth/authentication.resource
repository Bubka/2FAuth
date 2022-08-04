*** Settings ***
Documentation     A resource file for authentication tests.
...
Library           SeleniumLibrary
Resource          ../../common.resource

*** Variables ***
${VALID USER}     testing@2fauth.app
${VALID PASSWORD}    password