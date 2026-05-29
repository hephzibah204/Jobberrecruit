<?php

namespace Tests\Support\Helpers;

use CodeIgniter\Shield\Auth;
use CodeIgniter\Shield\Entities\User;
use Config\Services;

trait AuthTestHelper
{
    protected ?User $authTestUser = null;

    protected function setUpAuth(): void
    {
        $this->authTestUser = new User();
        $this->authTestUser->id = 9999;
        $this->authTestUser->status = 'active';
        $this->authTestUser->email_verified_at = date('Y-m-d H:i:s');
        $this->authTestUser->user_type = 'job_seeker';
        $this->authTestUser->email = 'test@example.com';
        $this->authTestUser->username = 'testuser';

        $testUser = $this->authTestUser;

        $mockAuth = $this->getMockBuilder(Auth::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['user', '__call'])
            ->getMock();

        $mockAuth->method('user')->willReturn($testUser);
        $mockAuth->method('__call')->willReturnCallback(function (string $method, array $args) use ($testUser): mixed {
            return match ($method) {
                'loggedIn' => true,
                'getUser'  => $testUser,
                default    => null,
            };
        });

        Services::injectMock('auth', $mockAuth);
    }
}
