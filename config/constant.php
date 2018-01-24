<?php
/**
 * Created by Mobio Solutions.
 * User: Nikhil Jain
 * Date: 19-01-2017
 * Time: 13:59
 */

return [
    'FROM_EMAIL' => 'contact@mobiosolutions.com',
    'FROM_NAME' => 'Aviva',
    'dashboard_machine' => 4,
    'permissions' => [
        1 => "Add",
        2 => "Edit",
        3 => "Export",
        4 => "Import",
    ],
    'user_type' => [
        1 => "Existing User",
        2 => "New User"
    ],
    /*
     * userDataTableFieldArray use for sorting
     * */
    "userDataTableFieldArray" => [

        "first_name",
        "last_name",
        "email",
        "mobile_number",
        "status"
    ],

    /*
     * app_userDataTableFieldArray use for filter
     * */
    "app_userDataTableFieldArray" => [

        "name",
        "email",
        "mobile_number",
        "country_id",
        "state_id",
        "",
        "",
        "status",
        ""
    ],

    /*
     * categoryDataTableFieldArray use for filter
     * */
    "categoryDataTableFieldArray" => [

        "category_name",
        "status",
        ""

    ],


    /*
     * machineDataTableFieldArray use for filter
     * */
    "machineDataTableFieldArray" => [
        "machine_name",
        "category_id",
        "",
        "",
        "status",
        ""

    ],

    /*
         * brochureDataTableFieldArray use for filter
         * */
    "brochureDataTableFieldArray" => [

        "name",
        "category_name",
        "machine_name",
        "status",
        ""

    ],

    /*
         * countryDataTableFieldArray use for filter
         * */
    "countryDataTableFieldArray" => [

        "name",
        "status",
        ""

    ],

    /*
         * countryDataTableFieldArray use for filter
         * */
    "stateDataTableFieldArray" => [

        "tbl_state.name",
        "tbl_country.name",
        "status",
        ""

    ],
    /*
         * countryDataTableFieldArray use for filter
         * */
    "newsDataTableFieldArray" => [

        "title",
        "status",
        ""

    ],
    /*
         * clientDataTableFieldArray use for filter
         * */
    "clientDataTableFieldArray" => [

        "name",
        "type",
        "status",
        ""

    ],

    /*
         * questionDataTableFieldArray use for filter
         * */
    "questionDataTableFieldArray" => [

        "question",
        "type",
        "view",
        "status",
        "",

    ],

    /*
         * questionDataTableFieldArray use for filter
         * */
    "requestDataTableFieldArray" => [

        "date",
        "name",
        "business_name",
        "mobile_number",
        "country_id",
        "state_id",
        "",

    ],


];

