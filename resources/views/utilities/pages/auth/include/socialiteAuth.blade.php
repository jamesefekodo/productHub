 {{-- social login start --}}

 <!-- Divider -->
 <div class="divider"><span>{{ t('auth.socialiteLoginTitle') }}</span></div>

 <!-- Social Buttons -->
 <div class="social-buttons">
     <button class="btn-social-gray" onclick="window.location.href='{{ route('googleAuthLogin') }}'" disabled>
         <!-- Google SVG Icon -->
         <!-- Compact Google 'G' Icon -->
         <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 533.5 544.3">
             <path fill="#4285F4"
                 d="M533.5 278.4c0-17.6-1.6-34.6-4.7-51.1H272v96.7h147.1c-6.4 34.6-25.8 63.9-55 83.6v69.4h88.8c51.9-47.8 82.6-118.1 82.6-198.6z" />
             <path fill="#34A853"
                 d="M272 544.3c74.6 0 137.1-24.7 182.8-67.2l-88.8-69.4c-24.6 16.5-56 26.3-94 26.3-72 0-133-48.5-154.9-113.9H28.5v71.4c45.3 89.6 138.1 152.8 243.5 152.8z" />
             <path fill="#FBBC05"
                 d="M117.6 321.5c-5.8-17.1-9.1-35.3-9.1-54s3.3-36.9 9.1-54V142.1H28.5c-18.7 36.1-29.4 76.9-29.4 119.4s10.7 83.3 29.4 119.4l89.1-71.4z" />
             <path fill="#EA4335"
                 d="M272 107.4c39.6 0 75 13.6 102.8 40.2l77.1-77.1C407.6 23.3 345.1 0 272 0 166.6 0 73.8 63.2 28.5 152.8l89.1 71.4c21.9-65.4 82.9-113.9 154.4-113.9z" />
         </svg>
         &nbsp;
         <span>Google</span>
     </button>
 </div>

 {{-- Social login stop --}}
