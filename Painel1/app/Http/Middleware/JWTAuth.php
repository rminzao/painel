<?php

namespace App\Http\Middleware;

use App\Models\User;
use Core\Session;
use Firebase\JWT\JWT;

/**
 *
 */
class JWTAuth
{
    /**
     * It takes a request object, extracts the Authorization header, and decodes the JWT. If the
     * decoded JWT contains an email, it finds the user by email and returns the user instance. If it
     * doesn't contain an email, it returns false
     *
     * @param request The request object.
     * @return The user object.
     */
    private function getJWTAuthUser($request)
    {
        //headers
        $headers = $request->getHeaders();

        //pure token jwt
        $jwt = isset($headers['Authorization']) ? str_replace('Bearer ', '', $headers['Authorization']) : '';

        //decode token
        try {
            $decode = (array) JWT::decode($jwt, $_ENV['JWT_KEY'], ['HS256']);
        } catch (\Exception $e) {
            throw new \Exception("access denied", 403);
        }

        //email
        $email = $decode['email'] ?? '';

        //find user by email
        $user = User::where('email', $email)->first();

        //check instance user
        return $user instanceof User ? $user : false;
    }

    /**
     * If the user is logged in, return the user object. If the user is not logged in, check if the
     * user is logged in via JWT. If the user is logged in via JWT, return the user object. If the user
     * is not logged in via JWT, return an error
     *
     * @param request The request object.
     * @return The user object.
     */
    private function auth($request)
    {
        $session = new Session();
        if ($session->has('uid')) {
            if ($user = User::find($session->uid)) {
                unset($user->password); 
                $request->user = $user->toArray();
                return;
            }
        }

        //check get user
        if ($user = $this->getJWTAuthUser($request)) {
            unset($user->password);
            $request->user = $user->toArray();
            return;
        }

        //return denied access
        throw new \Exception("access denied", 403);
    }

    /**
     * This function is called by the middleware handler.
     * It checks if the request has a valid JWT token.
     * If it does, it will execute the next middleware level.
     * If it doesn't, it will return a response with a 401 status code
     *
     * @param request The incoming request object.
     * @param next The next middleware in the chain.
     * @return The middleware is returning the () which is the next middleware in the
     * chain.
     */
    public function handle($request, $next)
    {
        //exec validation by jwt
        $this->auth($request);

        //execute next middleware level
        return $next($request);
    }
}
