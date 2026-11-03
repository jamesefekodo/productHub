<div class="outerFrame">
    <img src="{{ systemLogo() }}" alt="{{ env('APP_NAME') }}" class="" width="100px" style="margin: 1rem">

    <div class="mailContent">
        <p>
            {{ t('notifications.hello') }} {{ $user->first_name }},
        </p>

        <p>{{ t('notifications.email.authenticateLogin.paragraph1.sen1') }} <a
                href="{{ config('app.url') }}">{{ config('app.name') }}</a>.
            {{ t('notifications.email.authenticateLogin.paragraph1.sen2') }}</p>

        <div class="otp">
            <div class="otp-button">
                {{ $otp }}
            </div>
        </div>

        <p>{{ t('notifications.email.authenticateLogin.paragraph2') }}</p>

        <p>{{ t('notifications.email.authenticateLogin.paragraph3') }}</p>


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
