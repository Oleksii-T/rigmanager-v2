<?php

return [
    'profile' => [
        'invalid-curr-pass' => 'The provided password does not match your current password',
        'updated' => 'Profile updated successfully!',
        'updated-password' => 'Password updated successfully!',
        'image-deleted' => 'Profile picture was deleted successfully!',
    ],
    'inProgress' => 'Functionality is not ready yet',
    'success' => '',
    'error' => 'An error occured.',
    'info' => '',
    'authError' => 'You must be logged in to perform this action',
    'serverError' => 'Server error occurred',
    'accountActivated' => 'Your account has been verified!',

    'requireStandart' => 'The «Standart» account is required',
    'requirePro' => 'The «Pro» account is required',

    'postUploaded' => 'Post has been published successfully!',
    'postUploadedError' => 'Error occured while publishing the post.',
    'postDeleted' => 'Post has been deleted successfully!',
    'postDeleteError' => 'Error occured while deleting the post.',
    'postEdited' => 'Post has been changed successfully!',
    'postEditedError' => 'Error occured while editing the post.',
    'postAddedFav' => 'Post has been added to favourites!',
    'postAddFavError' => 'An error occured.',
    'postAddFavPersonal' => 'Your oun post can not be added to favourites.',
    'postRemovedFav' => 'Post has been deleted from favourites successfully!',
    'postRemoveFavError' => 'An error occured.',
    'postEditedErrorTooManyImages' => 'Too many images, maximum 5.',
    'postNewImgsDeleted' => 'Images have been cleared',
    'postImgsDeleted' => 'Images have been deleted',
    'postImgDeleted' => 'Image have been deleted',
    'postInputErrors' => 'Some fields are incorrect.',
    'postActivated' => 'Post has been published again',
    'postDisactivated' => 'Post has been hidden from public',
    'postOutdated' => 'This post is outdated, please update the lifetime in post settings',
    'tooManyPostsError' => 'You have reached the maximum amount of posts. (200 for Standart, 500 for Pro)',
    'allPostsDeleted' => 'All posts has been deleted!',
    'tooManyUrgentPostsError' => 'You have reached the maximum number of urgent posts. (100 for Standart, 300 for Pro)',

    'postTranslationEdited' => 'Post translation has been changed successfully!',

    'mailerToManyTags' => 'Too many categories',
    'mailersDeactivated' => 'All mailer deactivated successfully!',
    'mailerActivated' => 'Mailer has been activated successfully!',
    'mailerDeactivated' => 'Mailer has been deactivated successfully!',
    'mailerUploaded' => 'Mailer has been configured successfully!',
    'mailerUploadedError' => 'Error occured while configuring the Mailer.',
    'mailerDeleted' => 'Mailer has been deleted successfully!',
    'mailersDeleted' => 'All Mailers has been deleted successfully!',
    'mailerEditedError' => 'Error occured while editing the Mailer.',
    'mailerEdited' => 'Mailer has been changed successfully!',
    'mailerAddedAuthor' => 'Author added to Mailer',
    'mailerTooManyAuthors' => 'You have reached the maximum number of authors',
    'mailerRemovedAuthor' => 'Author removed from Mailer',
    'mailerAuthorExists' => 'Author already in Mailer',
    'mailerTagExists' => 'Specified categories already in your Mailer',
    'mailerTagAdded' => 'Specified categories added to your Mailer successfully!',
    'mailerTextAdded' => 'Specified keywords added to your Mailer successfully!',
    'tagAlreadyChosen' => 'You can not choose the same category twice!',
    'mailerRequestAdded' => 'The request has been added to Mailer successfully!',
    'mailerTooManyMailers' => 'Too many Mailers! (max. 10)',
    'mailerEmptyConditionsError' => 'At least one condition required!',
    'mailerEmptyTypesError' => 'At least one type required!',
    'mailerEmptyRolesError' => 'At least one legal type required!',
    'mailerEmptyThreadsError' => 'Please choose equipment or service',

    'signedIn' => 'Welcome!',
    'signedOut' => 'Good bye',

    'messageSent' => 'The message has been sent!',

    'planUpdated' => 'Your subscription has been changed successfully!',
    'planCanceled' => 'Your subscription has been canceled successfully!',


    'postImportError' => 'The error occurred while analyzing the import file',
    'postImportSuccess' => 'Posts published successfully',

    'import' => [
        'example-file-name' => 'rigmanager-import-file.xlsx',
        'errors' => [
            'AtPost' => 'An error occured at post #',
            'type' => 'Invalid "Type"'
        ]
    ],
    'importExtError' => 'Only xlsx files are allowed.',
    'importStructureError' => 'The import file structure is broken.',
    'importEmptyError' => 'The uploaded file is empty.',
    'importCompulsoryError' => 'Compulsory fields are not filled. Compulsory fields: Title, Description, Type, Role, Condition, Category, Email or Phone, Lifetime',
    'importTypeError' => '"Type" field is filled incorrectly. Only certain values ​​are allowed for input: "1" - Equipment sale; "2" - Equipment purchase; "3" - Equipment rent; "4" - Equipment lease',
    'importRoleError' => '"Role" field is filled incorrectly. Only certain values ​​are allowed for input: "1" - Private person; "2" - Business',
    'importConditionError' => '"Condition" field is filled incorrectly. Only certain values ​​are allowed for input: "1" - Not specified; "2" - New; "3" - Used; "4" - For spare parts',
    'importTagError' => '"Category" field is filled incorrectly. Category must be one of available category-code. See available category-codes on import rules page',
    'importRegionError' => '"Region" field is filled incorrectly. Region must be one of region-code. See available region-codes on import rules page',
    'importLifetimeError' => '"Lifetime" field is filled incorrectly. Only certain values ​​are allowed for input: "1" - 1 month; "2" - 2 months; "3" - Unlimited (Available for Pro accounts)',
    'importTitleError' => '"Title" field is filled incorrectly. Minimum 10 characters. Maximum 70 characters.',
    'importAmountError' => '"Amount" field is filled incorrectly. Example: "432 pcs.". Maximum 15 characters.',
    'importDescriptionError' => '"Description" field is filled incorrectly. Minimum 10 characters. Maximum 9000 characters.',
    'importCompanyError' => '"Company" field is filled incorrectly. Minimum 5 characters. Maximum 200 characters.',
    'importManufError' => '"Manufacturer" field is filled incorrectly. Minimum 3 characters. Maximum 70 characters.',
    'importManufDateError' => '"Manufectired date" field is filled incorrectly. Minimum 3 characters. Maximum 70 characters.',
    'importPNError' => '"Part number" field is filled incorrectly. Minimum 3 characters. Maximum 70 characters.',
    'importCostError' => '"Cost" field is filled incorrectly. Enter cost in format: [0000.00]. Maximum 20 digits. After the price, indicate the currency - "USD" or "UAH". Example: "999.9USD"',
    'importEmailError' => '"Email" field is filled incorrectly. You can enter any email address. Max lenght: 254',
    'importPhoneError' => '"Phone" field is filled incorrectly. Phone format: 0 (12) 345 67 89',
    'importTooManyPostsError' => 'You are trying to import :amount posts, but there are only :diff to maximum.',
    'importTooManyPremiums' => '',
    'importTooManyUrgents' => 'Too many urgent posts. You have :am. Maximum :max',

];
