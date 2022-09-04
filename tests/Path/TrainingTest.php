<?php

declare(strict_types=1);

namespace IncentiveFactory\IlEtaitUneFoisUnDev\Tests\Path;

use IncentiveFactory\IlEtaitUneFoisUnDev\Security\User;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\User\UserProviderInterface;

final class TrainingTest extends WebTestCase
{
    public function testShouldShowTrainingAndListItsCourses(): void
    {
        $client = static::createClient();

        /** @var UserProviderInterface $userProvider */
        $userProvider = $client->getContainer()->get(UserProviderInterface::class);

        /** @var User $user */
        $user = $userProvider->loadUserByIdentifier('player+1@email.com');

        $client->loginUser($user);

        $crawler = $client->request(Request::METHOD_GET, '/paths/trainings/training+1');
        self::assertResponseStatusCodeSame(Response::HTTP_OK);
        self::assertSelectorTextContains('h1', 'Training 1');
        self::assertCount(10, $crawler->filter('ul li'));
    }

    public function testShouldRaiseA404(): void
    {
        $client = static::createClient();

        /** @var UserProviderInterface $userProvider */
        $userProvider = $client->getContainer()->get(UserProviderInterface::class);

        /** @var User $user */
        $user = $userProvider->loadUserByIdentifier('player+1@email.com');

        $client->loginUser($user);

        $client->request(Request::METHOD_GET, '/paths/trainings/fail');
        self::assertResponseStatusCodeSame(Response::HTTP_NOT_FOUND);
    }
}
