<?php

return [
    'title' => 'Posts import policy',
    'intro' => 'Here is described the procedure and rules of',
    'introLink' => 'bulk posts import',
    'intro1' => 'from an XLSX file into rigmanagers.com.',
    'mainRulesTitle' => 'General rules',
    'mainRules1' => 'Changing the file structure makes automatic import impossible. Thus, removing columns, swap or move already created cells may result in form rejection.',
    'mainRules2' => 'You can fill out the form in cells B3:N502.',
    'mainRules3' => 'The form is designed to import from 1 to 500 posts.',
    'mainRules4' => 'Each line is a separate post.',
    'mainRules5' => 'The first post should be placed in cells B3:S3.',
    'mainRules6' => 'The form will be accepted only upon compliance with all the rules in ALL posts.',
    'mainRules7' => 'The algorithm analyzes the form line by line, if an error is found, the analysis will be stopped.',
    'mainRules8' => 'After importing the form, you will be told in which line and column the error was found.',
    'mainRules9' => 'The form must be completed in one language. Otherwise, the translation of your posts will be incorrect',
    'mainRules10' => 'You can set the "Urgent" status and change the auto-translation for each post manually through the post edit page on the site.',
    'detailedRules' => 'Detailed instructions for filling in each field.',
    'required' => 'Required',
    'titleRule' => 'Enter the name of the equipment / part / service.
        Should not contain words and synonyms: sell, buy, rent, rent, urgently, checked.
        Should not contain: telephone, email, link, words in capital letters (except for abbreviations).
        Minimum 10 characters.
        Maximum 255 characters.',
    'descRule' => 'Please describe your equipment / part / service in as much detail as possible.
        Should not contain: telephone, email, link, words in capital letters (except for abbreviations).
        Minimum 10 characters.
        Maximum 9000 characters.',
    'tag' => 'Category',
    'tagRule' => 'Should be one of category name from rigmanagers.com web platform. 
    See full list of categories on <a href="https://rigmanagers.com/categories">Categories</a> page.',
    'imagesRule' => 'Links to images. 
    You are free to isert few links devided by space or new line.',
    'type' => 'Type',
    'typeRule' => 'Only certain values ​a​re allowed for input:
        SELL - Equipment sale
        BUY - Equipment purchase
        RENT - Equipment rent
        LEASE - Equipment lease',
    'conditionRule' => 'Only certain values ​​are allowed for input:
        NEW - New
        USED - Used
        FOR-PARTS - For spare parts',
    'companyRule' => 'If you entered the value "2" in "Sector", you can enter the company name here.
        Minimum 5 characters.
        Maximum 200 characters.',
    'manufManufDatePN' => 'Manufacturer. Date of manufacture. Part number',
    'manufManufDatePNRule' => 'Additional data that is recommended to be included in each post.
    Free format.',
    'cost' => 'Cost-Currency',
    'currencyRule' => 'Cost of piece.
    Example value: $123,45',
    'regionRule' => 'Country code of equipment location.
    For example: CN.
    If not defined, country from profile will be taken',
    'lifetime' => 'Live period',
    'lifetimeRule' => 'Only certain values ​​are allowed for input:
        1M - 1 month
        2M - 2 months
        UNLIN - Unlimited (Available for Pro accounts)',
];
