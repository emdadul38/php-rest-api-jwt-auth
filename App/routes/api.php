<?php
    namespace App;
    use App\UserController;

    $Klein = new \Klein\Klein();

    /******************** User Routes || Authentication Routes **********************/
    $Klein->respond('POST', '/api/v1/user', [ new UserController(), 'createNewUser' ]);
    $Klein->respond('POST', '/api/v1/user-auth', [ new UserController(), 'login' ]);

    $Klein->respond(['GET', 'HEAD' ], '/api/v1/user/[:id]', [ new UserController(), 'testAuth' ]);

    // Dispatch all routes....
    $Klein->dispatch();
 ?>
