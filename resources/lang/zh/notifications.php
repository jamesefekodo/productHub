<?php
return [

    'hello' => '您好',
    'bestRegards' => '此致敬礼',
    'success' => '成功！',
    'error' => '错误！',
    'info' => '信息！',
    'warning' => '警告！',
    'emailSent' => '邮件发送成功！',
    'noRecordsFound' => '未找到记录！',
    'userNotFound' => '未找到用户记录！',
    'accessDenied' => '拒绝访问！',
    'done' => '完成！',
    'invalidCredentials' => '无效的登录凭证',
    'loggedOut' => '已退出登录！',
    'passwordDoNotMatch' => '两次输入的密码不一致！',
    'invalidCode' => '无效的验证码！',
    'otpExpired' => '一次性验证码已过期！',
    'incorrectOtp' => '验证码不正确！',
    'otpSent' => '一次性验证码发送成功！',

    'email' => [

        'welcomeNewUser' => [
            'title' => '欢迎加入 ',
            'paragraph1' => '欢迎您，感谢您注册我们的平台。',
            'paragraph2' => '我们专注于提供大规模供应解决方案，支持分销商、批发商及工业采购商。我们的平台为您提供具有竞争力的批量价格、稳定的产品供应以及高效便捷的订购流程。',
            'paragraph3' => [
                'line1' => '您将享有：',
                'uli1' => '适用于大批量订单的稳定库存',
                'uli2' => '透明定价及可扩展的供应方案',
                'uli3' => '安全交易与规范化履约流程',
                'uli4' => '专属批发客户支持服务',
            ],
            'paragraph4' => '我们的团队致力于支持您的业务运营与长期发展。如需报价、定制订单或物流协助，我们随时为您服务。',
            'paragraph5' => '我们的团队致力于支持您的业务运营与长期发展。如需报价、定制订单或物流协助，我们随时为您服务。',
            'paragraph6' => '感谢您选择我们作为您的供应合作伙伴。',
        ],

        'passwordResetMail' => [
            'title' => '重置密码',
            'paragraph1' => '我们收到了为您的账户重置密码的请求。',
            'paragraph2' => '请点击下方按钮以安全地创建新密码：',
            'resetPassword' => '重置您的密码',
            'paragraph3' => '为保障您的安全，此链接仅在限定时间内有效，且只能使用一次。',
            'paragraph4' => '如果您未申请重置密码，请忽略此邮件，您的账户不会受到任何影响。',
            'paragraph5' => '如需帮助或有任何疑问，我们的支持团队随时为您服务。',
        ],

        'userMailVerification' => [
            'title' => '验证邮箱',
            'paragraph1' => '感谢您注册 ',
            'paragraph2' => '我们需要确认这是您本人操作。请使用下方验证码验证您的邮箱地址：',
            'paragraph3' => '您的验证码：',
            'paragraph4' => '该验证码将在10分钟后失效。',
            'paragraph5' => '如果您未注册，请忽略此邮件。',
        ],

        'authenticateLogin' => [
            'title' => '登录验证',
            'paragraph1' => [
                'sen1' => '我们检测到您的账户在 ',
                'sen2' => '尝试登录。为确认是您本人，请使用以下一次性验证码（OTP）：',
            ],
            'paragraph2' => '该验证码将在10分钟后失效。',
            'paragraph3' => '如果这不是您本人操作，请忽略此邮件或联系我们的支持团队。',
        ],

        'newQuoteMailToAdmin' => [
            'title' => '新的报价请求 - 需要处理',
            'paragraph1' => '有一条新的报价请求已提交，正在等待审核。',
            'customerInfo' => '客户信息',
            'paragraph2' => '请尽快审核所请求的产品并准备报价。',
            'paragraph3' => '您可以通过管理员后台访问该请求。',
        ],

        'newQuoteMailToCustomer' => [
            'title' => '您的报价请求已收到',
            'paragraph1' => '感谢您提交报价请求。',
            'paragraph2' => '您的报价请求 (:slug) 已收到，正在处理中。',
            'paragraph3' => '我们将尽快与您联系，提供价格及库存详情。',
            'paragraph4' => '如有紧急问题，请随时联系我们。',
        ],

    ],

];
