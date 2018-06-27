<?php

namespace App\Helpers;

use App\Models\User;
use League\OAuth2\Server\Entities\ClientEntityInterface;
use League\OAuth2\Server\Exception\OAuthServerException;
use League\OAuth2\Server\Grant\PasswordGrant;
use League\OAuth2\Server\RequestEvent;
use Psr\Http\Message\ServerRequestInterface;
use League\OAuth2\Server\Entities\UserEntityInterface;

class PasswordOverrideGrant extends PasswordGrant
{
  protected function validateUser(ServerRequestInterface $request, ClientEntityInterface $client)
  {
    $entity_id = $this->getRequestParameter('entity_id', $request);
    if (is_null($entity_id)) {
        throw OAuthServerException::invalidRequest('entity_id');
    }

    $username = $this->getRequestParameter('username', $request);
    if (is_null($username)) {
        throw OAuthServerException::invalidRequest('username');
    }

    $password = $this->getRequestParameter('password', $request);
    if (is_null($password)) {
        throw OAuthServerException::invalidRequest('password');
    }
    // dd(User::where(['email'=>$username, 'entity_id'=>$entity_id])->exists());
    if (!User::where(['email'=>$username, 'entity_id'=>$entity_id])->exists()) {
        throw OAuthServerException::invalidCredentials();
    }
    $user = $this->userRepository->getUserEntityByUserCredentialsOverride(
        $username,
        $password,
        $this->getIdentifier(),
        $client,
        $entity_id
    );
    if ($user instanceof UserEntityInterface === false) {
        $this->getEmitter()->emit(new RequestEvent(RequestEvent::USER_AUTHENTICATION_FAILED, $request));

        throw OAuthServerException::invalidCredentials();
    }

    return $user;
  }
}