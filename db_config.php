<?php
const PARAMS = [
    "HOST" => 'localhost',
    "USER" => 'root',
    "PASS" => '',
    "DBNAME" => 'vet2',
    "CHARSET" => 'utf8mb4'
];


$dsn = "mysql:host=" . PARAMS['HOST'] . ";dbname=" . PARAMS['DBNAME'] . ";charset=" . PARAMS['CHARSET'];

$pdoOptions = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES => false
];


const SITE = 'http://localhost/vetUpNew/';

$actions = ['insert', 'update', 'delete'];


$messages = [
    0 => 'No direct access!',
    1 => 'Unknown user!',
    2 => 'User with this email already exists, choose another one!',
    3 => 'Check your email to active your account!',
    4 => 'Fill all the fields!',
    5 => 'You are logged out!!',
    6 => 'Your account is activated, you can login now! <a href="logIn.php" class="text-white">login</a>',
    7 => 'Passwords are not equal!',
    8 => 'Format of e-mail address is not valid! ',
    9 => 'Password is too short! It must be minimum 8 characters long!',
    10 => 'Password is not enough strong! (min 8 characters, at least 1 lowercase character, 1 uppercase character, 1 number, and 1 special character',
    11 => 'Something went wrong with mail server. We will try to send email later!',
    12 => 'Your account is already activated!',
    13 => 'If you have account on our site, email with instructions for reset password is sent to you.',
    14 => 'Something went wrong with server.',
    15 => 'Token or other data are invalid!',
    16 => 'Your new password is set and you can <a href="logIn.php" class="text-white">login</a>',
    17 => 'Your login is successful!',
    18 => 'It\'s an example email',
    19 => 'Successfully deleted',
    20 => 'Deleting failed',
    21 => 'Something went wrong during file upload!',
    22 => 'File is not a picture!!',
    23 => 'Error',
    24=> 'File with this name already exists!',
    25 =>'Update successfull!',
    26 => 'Update failed!',
    27 => 'Insert successfull!',
    28=> 'Insert failed!'

];

$emailMessages = [
    'signIn' => [
        'subject' => 'Register on web site',
        'altBody' => 'This is the body in plain text for non-HTML mail clients'
    ],
    'forgotten_password' => [
        'subject' => 'Forgotten password - create new password',
        'altBody' => 'This is the body in plain text for non-HTML mail clients'
    ],
    'admin_signIn' => [
    'subject' => 'Register on web site as Admin',
    'altBody' => 'This is the body in plain text for non-HTML mail clients'
    ],
    'vet_signIn' => [
        'subject' => 'Register on web site as Veterinarian',
        'altBody' => 'This is the body in plain text for non-HTML mail clients'
    ],
    'inesrtReservation' => [
        'subject' => 'Reservation successfull!',
        'altBody' => 'This is the body in plain text for non-HTML mail clients'
    ],

];