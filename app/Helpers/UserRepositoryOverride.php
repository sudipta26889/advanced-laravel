<?php

namespace App\Helpers;

use RuntimeException;
use Laravel\Passport\Bridge\User;
use Illuminate\Hashing\HashManager;
use Illuminate\Contracts\Hashing\Hasher;
use League\OAuth2\Server\Entities\ClientEntityInterface;
use League\OAuth2\Server\Repositories\UserRepositoryInterface;
use Laravel\Passport\Bridge\UserRepository;

class UserRepositoryOverride extends UserRepository {
	public function getUserEntityByUserCredentialsOverride($username, $password, $grantType, ClientEntityInterface $clientEntity, $entity_id) {
        $provider = config('auth.guards.api.provider');

        if (is_null($model = config('auth.providers.'.$provider.'.model'))) {
            throw new RuntimeException('Unable to determine authentication model from configuration.');
        }
        
        if (method_exists($model, 'findForPassport')) {
            $user = (new $model)->findForPassport($username);
        } else {
            $user = (new $model)->where(['email' => $username, 'entity_id' => $entity_id])->first();
        }
        
        if (! $user) {
            return;
        } elseif (method_exists($user, 'validateForPassportPasswordGrant')) {
            if (! $user->validateForPassportPasswordGrant($password)) {
                return;
            }
        } elseif (! $this->hasher->check($password, $user->getAuthPassword())) {
            return;
        }
        
        return new User($user->getAuthIdentifier());
    }
}