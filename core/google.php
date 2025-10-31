<?php

return [
    'client_id' => $TN->site('google_id'),
    'client_secret' => $TN->site('google_secret'),
    'redirect_uri' => BASE_URL('google-callback'),
    'scope' => 'https://www.googleapis.com/auth/userinfo.email https://www.googleapis.com/auth/userinfo.profile',
];
?>
