<?php

namespace Tests\Data;

class HttpRequestTestData
{
    const TAG_NAME = 'v3.4.1';

    const NEW_TAG_NAME = 'v999.999.999';

    const SVG_LOGO_BODY = '<svg xmlns="http://www.w3.org/2000/svg" class="r-k200y r-13gxpu9 r-4qtqp9 r-yyyyoo r-np7d94 r-dnmrzs r-bnwqim r-1plcrui r-lrvibr" width="22.706" height="22.706"><path d="M22.706 4.311c-.835.37-1.732.62-2.675.733a4.67 4.67 0 002.048-2.578 9.3 9.3 0 01-2.958 1.13 4.66 4.66 0 00-7.938 4.25 13.229 13.229 0 01-9.602-4.868c-.4.69-.63 1.49-.63 2.342a4.66 4.66 0 002.072 3.878 4.647 4.647 0 01-2.11-.583v.06a4.66 4.66 0 003.737 4.568 4.692 4.692 0 01-2.104.08 4.661 4.661 0 004.352 3.234 9.348 9.348 0 01-5.786 1.995A9.5 9.5 0 010 18.487a13.175 13.175 0 007.14 2.093c8.57 0 13.255-7.098 13.255-13.254 0-.2-.005-.402-.014-.602a9.47 9.47 0 002.323-2.41z" fill="#1da1f2"/></svg>';

    const TFA_JSON_BODY = '
    [
        [
            "Twitch",
            {
                "domain": "twitch.tv",
                "url": "https://www.twitch.tv/",
                "tfa":
                [
                    "sms",
                    "custom-software",
                    "totp"
                ],
                "custom-software":
                [
                    "Authy"
                ],
                "documentation": "https://help.twitch.tv/s/article/two-factor-authentication",
                "notes": "To activate two factor authentication, you must provide a mobile phone number.",
                "keywords":
                [
                    "entertainment"
                ]
            }
        ],
        [
            "Twitter",
            {
                "domain": "twitter.com",
                "tfa":
                [
                    "sms",
                    "totp",
                    "u2f"
                ],
                "documentation": "https://help.twitter.com/en/managing-your-account/two-factor-authentication",
                "recovery": "https://help.twitter.com/en/managing-your-account/issues-with-login-authentication",
                "notes": "SMS only available on select providers.",
                "keywords":
                [
                    "social"
                ]
            }
        ],
        [
            "Txbit",
            {
                "domain": "txbit.io",
                "tfa":
                [
                    "totp"
                ],
                "documentation": "https://support.txbit.io/support/solutions/articles/44000447137",
                "keywords":
                [
                    "cryptocurrencies"
                ]
            }
        ]
    ]';

    const LATEST_RELEASE_BODY_NO_NEW_RELEASE = '
    {
        "url": "https://api.github.com/repos/Bubka/2FAuth/releases/84186611",
        "assets_url": "https://api.github.com/repos/Bubka/2FAuth/releases/84186611/assets",
        "upload_url": "https://uploads.github.com/repos/Bubka/2FAuth/releases/84186611/assets{?name,label}",
        "html_url": "https://github.com/Bubka/2FAuth/releases/tag/' . self::TAG_NAME . '",
        "id": 84186611,
        "author": {
            "login": "Bubka",
            "id": 858858,
            "node_id": "MDQ6VXNlcjg1ODg1OA==",
            "avatar_url": "https://avatars.githubusercontent.com/u/858858?v=4",
            "gravatar_id": "",
            "url": "https://api.github.com/users/Bubka",
            "html_url": "https://github.com/Bubka",
            "followers_url": "https://api.github.com/users/Bubka/followers",
            "following_url": "https://api.github.com/users/Bubka/following{/other_user}",
            "gists_url": "https://api.github.com/users/Bubka/gists{/gist_id}",
            "starred_url": "https://api.github.com/users/Bubka/starred{/owner}{/repo}",
            "subscriptions_url": "https://api.github.com/users/Bubka/subscriptions",
            "organizations_url": "https://api.github.com/users/Bubka/orgs",
            "repos_url": "https://api.github.com/users/Bubka/repos",
            "events_url": "https://api.github.com/users/Bubka/events{/privacy}",
            "received_events_url": "https://api.github.com/users/Bubka/received_events",
            "type": "User",
            "site_admin": false
        },
        "node_id": "RE_kwDOCyNVx84FBJXz",
        "tag_name": "' . self::TAG_NAME . '",
        "target_commitish": "master",
        "name": "' . self::TAG_NAME . '",
        "draft": false,
        "prerelease": false,
        "created_at": "2022-11-25T13:31:45Z",
        "published_at": "2022-11-25T13:44:10Z",
        "assets": [

        ],
        "tarball_url": "https://api.github.com/repos/Bubka/2FAuth/tarball/' . self::TAG_NAME . '",
        "zipball_url": "https://api.github.com/repos/Bubka/2FAuth/zipball/' . self::TAG_NAME . '",
        "body": "### Fixed\r\n\r\n- [issue #140](https://github.com/Bubka/2FAuth/issues/140) Bad regex for Period field (advanced form)\r\n- [issue #141](https://github.com/Bubka/2FAuth/issues/141) Digits field is missing in advanced form"
    }';

    const LATEST_RELEASE_BODY_NEW_RELEASE = '
    {
        "url": "https://api.github.com/repos/Bubka/2FAuth/releases/84186611",
        "assets_url": "https://api.github.com/repos/Bubka/2FAuth/releases/84186611/assets",
        "upload_url": "https://uploads.github.com/repos/Bubka/2FAuth/releases/84186611/assets{?name,label}",
        "html_url": "https://github.com/Bubka/2FAuth/releases/tag/' . self::NEW_TAG_NAME . '",
        "id": 84186611,
        "author": {
            "login": "Bubka",
            "id": 858858,
            "node_id": "MDQ6VXNlcjg1ODg1OA==",
            "avatar_url": "https://avatars.githubusercontent.com/u/858858?v=4",
            "gravatar_id": "",
            "url": "https://api.github.com/users/Bubka",
            "html_url": "https://github.com/Bubka",
            "followers_url": "https://api.github.com/users/Bubka/followers",
            "following_url": "https://api.github.com/users/Bubka/following{/other_user}",
            "gists_url": "https://api.github.com/users/Bubka/gists{/gist_id}",
            "starred_url": "https://api.github.com/users/Bubka/starred{/owner}{/repo}",
            "subscriptions_url": "https://api.github.com/users/Bubka/subscriptions",
            "organizations_url": "https://api.github.com/users/Bubka/orgs",
            "repos_url": "https://api.github.com/users/Bubka/repos",
            "events_url": "https://api.github.com/users/Bubka/events{/privacy}",
            "received_events_url": "https://api.github.com/users/Bubka/received_events",
            "type": "User",
            "site_admin": false
        },
        "node_id": "RE_kwDOCyNVx84FBJXz",
        "tag_name": "' . self::NEW_TAG_NAME . '",
        "target_commitish": "master",
        "name": "' . self::NEW_TAG_NAME . '",
        "draft": false,
        "prerelease": false,
        "created_at": "2022-12-25T13:31:45Z",
        "published_at": "2022-12-25T13:44:10Z",
        "assets": [

        ],
        "tarball_url": "https://api.github.com/repos/Bubka/2FAuth/tarball/' . self::NEW_TAG_NAME . '",
        "zipball_url": "https://api.github.com/repos/Bubka/2FAuth/zipball/' . self::NEW_TAG_NAME . '",
        "body": "### Fixed\r\n\r\n- [issue #140](https://github.com/Bubka/2FAuth/issues/140) Bad regex for Period field (advanced form)\r\n- [issue #141](https://github.com/Bubka/2FAuth/issues/141) Digits field is missing in advanced form"
    }';

    const ICON_PNG = 'iVBORw0KGgoAAAANSUhEUgAAABAAAAAQCAIAAACQkWg2AAAAsUlEQVR4AWN44aVBEhoCGl4GGLzND/nYW/Fpdsf7urTX8Q74NLwtjf7z+vl/VPDzwvFX4eYIDUhm6//99AGi6PfDOz9OH4Tr+TSrHYuG1/GOn+f3AtGnOV0vvLXeZPr8+/IJouHbthU4nJQfAtQANBuuFJ+GDx2F///9g6gAMn5dOfP34zt8Gr7tWQ838n1DBlDk973r+DS8Sff+snQKBL2KsQOKfJzSAOFC9EPQcEhLAD5LqIU3S31+AAAAAElFTkSuQmCC';
}
