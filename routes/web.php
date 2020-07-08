<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

// teste jwt
// Route::get('/jwt', function() {

//     $signer = new \Lcobucci\JWT\Signer\Hmac\Sha256();
//     $time = time();
//     $token = (new \Lcobucci\JWT\Builder())->issuedBy('http://example.test')
//                                             ->permittedFor('http://example.org')
//                                             ->identifiedBy('9999999999')
//                                             ->issuedAt($time)
//                                             ->canOnlyBeUsedAfter($time+60)
//                                             ->expiresAt($time+3600)
//                                             ->withClaim('uid', 1)
//                                             ->withClaim('email', 'teste@teste.com')
//                                             ->getToken($signer, new \Lcobucci\JWT\Signer\Key('teste'));
    
//     return $token;                                            

//     $key = '12345678';

//     $header = [
//         'typ' => 'JWT',
//         'alg' => 'HS256'
//     ];

//     $payload = [
//         'exp' => (new DateTime('now'))->getTimestamp(),
//         'uid' => 1,
//         'email' => 'teste@teste.com'
//     ];

//     $header = base64_encode(json_encode($header));
//     $payload = base64_encode(json_encode($payload));

//     $sign = base64_encode(hash_hmac('sha256', $header.'.'.$payload, $key, true));

//     $token = $header.'.'.$payload.'.'.$sign;

//     return $token;
// });
