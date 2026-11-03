<div class="outerFrame">
    <img src="{{ systemLogo() }}" alt="{{ env('APP_NAME') }}" class="" width="100px"
        style="margin: 1rem">

    <div class="mailContent">
        <p>
            {{ t('notifications.hello') }},
        </p>

        <p>{{ t('notifications.email.welcomeNewUser.paragraph1') }}</p>

        <p>{{ t('notifications.email.welcomeNewUser.paragraph2') }}</p>

        <p class="m-0 p-0">{{ t('notifications.email.welcomeNewUser.paragraph3.line1') }}</p>

        <ul>
            <li>{{ t('notifications.email.welcomeNewUser.paragraph3.uli1') }}</li>
            <li>{{ t('notifications.email.welcomeNewUser.paragraph3.uli2') }}</li>
            <li>{{ t('notifications.email.welcomeNewUser.paragraph3.uli3') }}</li>
            <li>{{ t('notifications.email.welcomeNewUser.paragraph3.uli4') }}</li>
        </ul>

        <p>{{ t('notifications.email.welcomeNewUser.paragraph4') }}</p>

        <p>{{ t('notifications.email.welcomeNewUser.paragraph5') }}</p>

        <p>{{ t('notifications.email.welcomeNewUser.paragraph6') }}</p>

        <br>


        <p style="margin: 0">
            {{ t('notifications.bestRegards') }},
        </p>
        <p style="margin: 0">
            {{ config('app.name') }}.
        </p>
    </div>

</div>



<style>
    .outerFrame {
        width: 100%;
        background: #f0f0f0;
        text-align: center;
        padding: 1rem;
    }

    .mailContent {
        width: 90%;
        background: #ffffff;
        text-align: left;
        margin: 0.3rem auto;
        padding: 1rem;
        font-family: Arial, sans-serif;
    }

    .mailLink {
        text-align: center;
    }

    .link-button {
        display: inline-block;
        padding: 10px 20px;
        background-color: #145BD9;
        color: #fff;
        text-decoration: none;
        border-radius: 5px;
        border: none;
        cursor: pointer;
        transition: background-color 0.3s;
    }

    .link-button:hover {
        background-color: #77c3ec;
    }

    .otp {
        text-align: center;
        justify-content: center;
    }

    .link-button {
        display: inline-block;
        padding: 10px 20px;
        background-color: #145BD9;
        color: #fff;
        text-decoration: none;
        border-radius: 5px;
        border: none;
        cursor: pointer;
        transition: background-color 0.3s;
    }


    .otp-button {
        display: inline-block;
        padding: 10px 20px;
        background-color: #145BD9;
        color: #fff;
        font-size: 1.5rem;
        text-decoration: none;
        border-radius: 5px;
        border: none;
        cursor: pointer;
        transition: background-color 0.3s;
    }

    .otp-button:hover {
        background-color: #77c3ec;
    }
</style>
