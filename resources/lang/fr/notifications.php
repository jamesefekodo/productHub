<?php
return [
    'hello' => 'Bonjour',
    'bestRegards' => 'Cordialement',
    'success' => 'Succès !',
    'error' => 'Erreur !',
    'info' => 'Information !',
    'warning' => 'Avertissement !',
    'emailSent' => 'Email envoyé avec succès !',
    'noRecordsFound' => 'Aucun enregistrement trouvé !',
    'userNotFound' => 'Utilisateur introuvable !',
    'accessDenied' => 'Accès refusé !',
    'done' => 'Terminé !',
    'invalidCredentials' => 'Identifiants invalides',
    'loggedOut' => 'Déconnecté !',
    'passwordDoNotMatch' => 'Les mots de passe ne correspondent pas !',
    'invalidCode' => 'Code invalide !',
    'otpExpired' => 'Le code OTP a expiré !',
    'incorrectOtp' => 'OTP incorrect !',
    'otpSent' => 'OTP envoyé avec succès !',

    'email' => [
        'welcomeNewUser' => [
            'title' => 'Bienvenue sur ',
            'paragraph1' => 'Bienvenue, et merci de vous être inscrit chez nous.',
            'paragraph2' => 'Nous sommes spécialisés dans les solutions d’approvisionnement à grande échelle conçues pour soutenir les distributeurs, grossistes et acheteurs industriels. Notre plateforme vous donne accès à des prix de gros compétitifs, une disponibilité constante des produits et un processus de commande optimisé pour l’efficacité.',
            'paragraph3' => [
                'line1' => 'Vous pouvez vous attendre à :',
                'uli1' => 'Un stock fiable pour les commandes en grande quantité',
                'uli2' => 'Des prix transparents et des options d’approvisionnement évolutives',
                'uli3' => 'Des transactions sécurisées et une exécution structurée des commandes',
                'uli4' => 'Un support dédié aux clients grossistes',
            ],
            'paragraph4' => 'Notre équipe s’engage à soutenir vos opérations commerciales et votre croissance à long terme. Si vous avez besoin de devis, de commandes personnalisées ou d’assistance logistique, nous sommes à votre disposition.',
            'paragraph5' => 'Notre équipe s’engage à soutenir vos opérations commerciales et votre croissance à long terme. Si vous avez besoin de devis, de commandes personnalisées ou d’assistance logistique, nous sommes à votre disposition.',
            'paragraph6' => 'Merci de nous avoir choisis comme partenaire d’approvisionnement.',
        ],

        'passwordResetMail' => [
            'title' => 'Réinitialisation du mot de passe',
            'paragraph1' => 'Nous avons reçu une demande de réinitialisation du mot de passe de votre compte.',
            'paragraph2' => 'Pour continuer, veuillez cliquer sur le bouton ci-dessous afin de créer un nouveau mot de passe en toute sécurité :',
            'resetPassword' => 'Réinitialiser votre mot de passe',
            'paragraph3' => 'Ce lien est valable pour une durée limitée et ne peut être utilisé qu’une seule fois pour votre sécurité.',
            'paragraph4' => 'Si vous n’avez pas demandé de réinitialisation de mot de passe, vous pouvez ignorer cet email en toute sécurité — aucune modification ne sera apportée à votre compte.',
            'paragraph5' => 'Si vous avez besoin d’assistance ou avez des questions, notre équipe de support est à votre disposition.',
        ],

        'userMailVerification' => [
            'title' => 'Vérification de l’email',
            'paragraph1' => 'Merci de vous être inscrit sur ',
            'paragraph2' => 'Nous devons simplement confirmer qu’il s’agit bien de vous. Veuillez utiliser le code ci-dessous pour vérifier votre adresse email :',
            'paragraph3' => 'Votre code de vérification : ',
            'paragraph4' => 'Ce code expirera dans 10 minutes.',
            'paragraph5' => 'Si vous ne vous êtes pas inscrit, aucun souci — vous pouvez ignorer cet email.',
        ],

        'authenticateLogin' => [
            'title' => 'Authentification de la connexion',
            'paragraph1' => [
                'sen1' => 'Nous avons remarqué une tentative de connexion à votre compte sur ',
                'sen2' => 'Pour confirmer qu’il s’agit bien de vous, veuillez utiliser le mot de passe à usage unique (OTP) ci-dessous :',
            ],
            'paragraph2' => 'Ce code expirera dans 10 minutes.',
            'paragraph3' => 'Si vous n’êtes pas à l’origine de cette tentative de connexion, veuillez ignorer cet email ou contacter notre support.',
        ],

        'newQuoteMailToAdmin' => [
            'title' => 'Nouvelle demande de devis – Action requise',
            'paragraph1' => 'Une nouvelle demande de devis vient d’être soumise et est en attente de validation.',
            'customerInfo' => 'Informations client',
            'paragraph2' => 'Veuillez examiner les articles demandés et préparer le devis dans les plus brefs délais.',
            'paragraph3' => 'Vous pouvez accéder à la demande depuis votre tableau de bord administrateur.',
        ],

        'newQuoteMailToCustomer' => [
            'title' => 'Votre demande de devis a été reçue',
            'paragraph1' => 'Merci d’avoir soumis votre demande de devis.',
            'paragraph2' => 'Votre demande de devis (:slug) a bien été reçue et est en cours de traitement.',
            'paragraph3' => 'Nous vous contacterons prochainement avec les détails des prix et de la disponibilité.',
            'paragraph4' => 'Si vous avez des questions urgentes, n’hésitez pas à nous contacter.',
        ],
    ],
];
