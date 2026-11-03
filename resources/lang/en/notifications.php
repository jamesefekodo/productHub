<?php
return [
    'hello' => 'Hello',
    'bestRegards' => 'Best regards',
    'success' => 'Success!',
    'error' => 'Error!',
    'info' => 'Info!',
    'warning' => 'Warning!',
    'emailSent' => 'Email Sent Successfully!',
    'noRecordsFound' => 'No records found!',
    'userNotFound' => 'User record not found!',
    'accessDenied' => 'Access Denied!',
    'done' => 'Done!',
    'invalidCredentials' => 'Invalid Credentials',
    'loggedOut' => 'Logged Out!',
    'passwordDoNotMatch' => 'Passwords do not match!',
    'invalidCode' => 'Invalid Code!',
    'otpExpired' => 'OTP has expired!',
    'incorrectOtp' => 'Incorrect OTP!',
    'otpSent' => 'OTP Sent Successfully!',
    'alreadyInQuote' => 'Already In Quote!',
    'alreadyExists' => 'Already Exists!',
    'templateDoesNotExist' => 'Template not found. Please create one!',
    'returnEmptyQuote' => 'This quote is empty. Please add products before continuing!',
    'returnNoClientQuote' => 'Client details not found!',
    'returnNoNote' => 'Please, check terms & conditions and other notes!',
    'returnNoDates' => 'Please, check issuance and expiry dates!',




    'email' => [
        'welcomeNewUser' => [
            'title' => 'Welcome to ',
            'paragraph1' => 'Welcome, and thank you for registering with us.',
            'paragraph2' => 'We specialize in large-scale supply solutions designed to support distributors, wholesalers, and industrial buyers. Our platform gives you access to competitive bulk pricing, consistent product availability, and a streamlined ordering process built for efficiency.',
            'paragraph3' => [
                'line1' => 'You can expect:',
                'uli1' => 'Reliable inventory for high-volume orders',
                'uli2' => 'Transparent pricing and scalable supply options',
                'uli3' => 'Secure transactions and structured fulfilment',
                'uli4' => 'Dedicated support for wholesale customers',
            ],
            'paragraph4' => 'Our team is committed to supporting your business operations and long-term growth. Should you require quotations, custom orders, or logistical assistance, we’re ready to help.',
            'paragraph5' => 'Our team is committed to supporting your business operations and long-term growth. Should you require quotations, custom orders, or logistical assistance, we’re ready to help.',
            'paragraph6' => 'Thank you for choosing us as your supply partner.',
        ],

        'passwordResetMail' => [
            'title' => 'Reset Password',
            'paragraph1' => 'We received a request to reset the password for your account.',
            'paragraph2' => 'To continue, please click the button below to securely create a new password:',
            'resetPassword' => 'Reset Your Password',
            'paragraph3' => 'This link is valid for a limited time and can only be used once for your protection.',
            'paragraph4' => 'If you did not request a password reset, you can safely ignore this email — no changes will be made to your account.',
            'paragraph5' => 'If you need assistance or have questions, our support team is ready to help.',
        ],

        'userMailVerification' => [
            'title' => 'Verify Email',
            'paragraph1' => 'Thank you for registering on ',
            'paragraph2' => 'We just need to confirm that this is really you. Please use the code below to verify your email address:',
            'paragraph3' => 'Your Verification Code: ',
            'paragraph4' => 'This code will expire in 10 minutes.',
            'paragraph5' => 'If you didn\'t sign up, no worries - you can safely ignore this email.',
        ],

        'authenticateLogin' => [
            'title' => 'Authenticate Login',
            'paragraph1' => [
                'sen1' => 'We noticed a login attempt for your account on ',
                'sen2' => 'To verify it\'s you, please use the One-Time Password (OTP) below:',
            ],
            'paragraph2' => 'This code will expire in 10 minutes.',
            'paragraph3' => 'If you did not attempt to log in, please ignore this email or contact our support.',
        ],

        'newQuoteMailToAdmin' => [
            'title' => 'New Quote Request - Action Required',
            'paragraph1' => 'A new quote request has just been submitted and is awaiting review.',
            'customerInfo' => 'Customer Information',
            'paragraph2' => 'Please review the requested items and prepare the quotation at your earliest convenience.',
            'paragraph3' => 'You can access the request from your admin dashboard.',
        ],

        'newQuoteMailToCustomer' => [
            'title' => 'Your Quote Request Has Been Received',
            'paragraph1' => 'Thank you for submitting your quote request.',
            'paragraph2' => 'Your quote request (:slug) has been received and is being processed.',
            'paragraph3' => 'We will contact you shortly with pricing and availability details.',
            'paragraph4' => 'If you have any urgent questions, please feel free to reach out to us.',
        ],
        'newQuoteGeneratedAndSent' => [
           'title' => 'New Quote Received ',
           'paragraph1' => 'Please find attached the PDF file containing your quote.',
        ]
    ],
];
