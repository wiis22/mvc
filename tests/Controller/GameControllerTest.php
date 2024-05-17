<?php

namespace App\Controller;

// use PHPUnit\Framework\TestCase;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use App\Card\DeckOfCardsGraphic;
use App\AGame21\Player;

class GameControllerTest extends WebTestCase
{
    public function testStart(): void
    {

        //create a mock session
        $sessionMock = $this->createMock(SessionInterface::class);
        //seting it up
        // $sessionMock->expects($this->once())->method('clear');
        $sessionMock->expects($this->any())
            ->method('get')
            ->withConsecutive(['player_hand'], ['bank_hand'], ['deck21'])
            ->willReturnOnConsecutiveCalls(
                new Player(),
                new Player(),
                new DeckOfCardsGraphic()
            );

        //container setup
        $client = static::createClient();
        $client->getContainer()->set(SessionInterface::class, $sessionMock);

        // $container = $client->getContainer();

        // $session = container->get(SessionInterface::class);


        // $container->set(SessionInterface::class, $sessionMock);

        $client->request('GET', '/game/start');

        $this->assertResponseIsSuccessful();

        $this->assertInstanceOf(Player::class, $sessionMock->get("player_hand"));
        $this->assertInstanceOf(Player::class, $sessionMock->get("bank_hand"));
        $this->assertInstanceOf(DeckOfCardsGraphic::class, $sessionMock->get("deck21"));
    }
}
