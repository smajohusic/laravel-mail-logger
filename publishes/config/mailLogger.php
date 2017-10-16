<?php

return [
    /*
     * Enable auto deleting of log.
     */
    'enableAutoDeletion' => false,

    /*
     * Set how long a entry should be in the database. Remember that enableAutoDeletion has to be true.
     */
    'keepLogsForDays' => 30,

    /*
     * Define all possible input field names your app uses when sending the email. This will then be used to get the
     * field value from the request
     */
    'toEmailAddresses' => [
        'to',
        'email',
    ],
];
