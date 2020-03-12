<?php

use Illuminate\Http\Request;

$DoctorPrefix   = 'd1';
$CustomerPrefix = 'c1';
$WebsitePrefix  = 'w1';

include base_path('routes/apiChatbot.php');

include base_path('routes/apiDoctor.php');

include base_path('routes/apiCustomer.php');

include base_path('routes/apiWebsite.php');

