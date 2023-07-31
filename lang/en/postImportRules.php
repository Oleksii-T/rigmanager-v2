<?php

return [
    'title' => 'Posts import policy',
    'intro' => 'Here is described the procedure and rules of',
    'introLink' => 'bulk posts import',
    'intro1' => 'from an XLSX file into rigmanagers.com.',
    'mainRulesTitle' => 'General rules',
    'mainRules1' => 'The file designed for bulk uploading of posts about equipment. Thus, posts сoncerning services must be uploaded manually.',
    'mainRules2' => 'Changing the file structure makes automatic import impossible. Thus, adding / removing columns / pages, swapping or moving already created cells is highly discouraged.',
    'mainRules3' => 'Filling out the form is allowed on cells B3:S502',
    'mainRules4' => 'The form is designed to import from 1 to 500 posts.',
    'mainRules5' => 'Each line is a separate post.',
    'mainRules6' => 'The first announcement should be placed in cells B3:S3.',
    'mainRules7' => 'The form will be accepted only upon compliance with all the rules in all posts.',
    'mainRules8' => 'The algorithm analyzes the shape line by line; if an error is found, the analysis will be stopped.',
    'mainRules9' => 'After importing the form, you will be told in which declaration the error was found.',
    'mainRules10' => 'The form must be completed in one language (Rus / Ukr / Eng).',
    'mainRules11' => 'You can add images, set the "Urgent" status and change the auto-translation for each ad manually through the ad edit page on the site.',
    'detailedRules' => 'Detailed instructions for filling in each field.',
    'required' => 'Required',
    'titleRule' => 'Enter the name of the equipment / part / service.
        Should not contain words and synonyms: sell, buy, rent, rent, urgently, checked, rigmanager.
        Should not contain: telephone, email, link, words in capital letters (except for abbreviations).
        Minimum 10 characters.
        Maximum 70 characters.',
    'descRule' => 'Please describe your equipment / part / service in as much detail as possible.
        Should apply to all information provided on row (post).
        Should not contain: telephone, email, link, words in capital letters (except for abbreviations).
        Minimum 10 characters.
        Maximum 9000 characters.',
    'companyRule' => 'If you entered the value "2" in "Sector", you can enter the company name here.
        Minimum 5 characters.
        Maximum 200 characters.',
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
    'tag' => 'Category',
    'tagRule' => 'Tag must be one of available category-code, click the button below to see the full list of available codes.',
    'tagRuleEqBtn' => 'See list of equipment category codes',
    'tagsEqList' => 'List of equipment categories codes',
    'tagRuleSeBtn' => 'See list of service category codes',
    'tagsSeList' => 'List of service categories codes',
    'manufManufDatePN' => 'Manufacturer. Date of manufacture. Part number',
    'manufManufDatePNRule' => 'We advise adding manufacturer, manufacturing date and part number to make it easier for customers to find and understand your proposal.
        Minimum 3 characters.
        Maximum 70 characters.',
    'cost' => 'Cost-Currency',
    'currencyRule' => 'After the price, indicate the currency - "USD" or "UAH". Example: "999.9USD"',
    'regionRule' => 'Region must be one of region-code, click the button below to see the full list of available codes.',
    'lifetime' => 'Live period',
    'lifetimeRule' => 'Only certain values ​​are allowed for input:
        1M - 1 month
        2M - 2 months
        UNLIN - Unlimited (Available for Pro accounts)',
];
