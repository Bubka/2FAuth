<?php

return [
    /*
    |----------------------------------------------------------------------
    | Auto backup mode
    |----------------------------------------------------------------------
    |
    | This value is used when you save your file content. If value is true,
    | the original file will be backed up before save.
    */

    'autoBackup' => false,

    /*
    |----------------------------------------------------------------------
    | Backup location
    |----------------------------------------------------------------------
    |
    | This value is used when you backup your file. This value is the sub
    | path from root folder of project application.
    */

    'backupPath' => base_path('storage/dotenv-backups/'),

    /*
    |----------------------------------------------------------------------
    | Always create backup folder
    |----------------------------------------------------------------------
    |
    | If this setting is set to true, the backup folder set up in the
    | 'backupPath' setting will always be created regardless of whether the
    | backup is performed or not.
    */

    'alwaysCreateBackupFolder' => false,
];
