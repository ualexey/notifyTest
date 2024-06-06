<?php

namespace App\Tests\Cli;

use App\Cli\NotificationCli;
use App\Domain\Handler\CliHandlerInterface;
use Exception;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;

class NotificationCliTest extends TestCase
{
    private CliHandlerInterface $cliHandlerMock;
    private CommandTester $commandTester;
    private NotificationCli $command;

    protected function setUp(): void
    {
        $this->cliHandlerMock = $this->createMock(CliHandlerInterface::class);

        $this->command = new NotificationCli($this->cliHandlerMock);

        $application = new Application();
        $application->add($this->command);

        $commandToTest = $application->find('send:delayed');
        $this->commandTester = new CommandTester($commandToTest);
    }

    public function testConfigure(): void
    {
        $command = $this->command;

        $this->assertEquals('send:delayed', $command->getName());
        $this->assertEquals('This command tries to resend messages', $command->getDescription());
        $this->assertTrue($command->getDefinition()->hasArgument('userId'));
    }

    public function testExecuteSuccess(): void
    {
        $this->cliHandlerMock->expects($this->once())
            ->method('handle')
            ->with('12345');

        $this->commandTester->execute([
            'userId' => '12345',
        ]);

        $output = $this->commandTester->getDisplay();
        $this->assertStringContainsString('Messages have been successfully sent.', $output);
        $this->assertEquals(0, $this->commandTester->getStatusCode());
    }

    public function testExecuteFailure(): void
    {
        $this->cliHandlerMock->expects($this->once())
            ->method('handle')
            ->with('12345')
            ->willThrowException(new Exception('Something went wrong'));

        $this->commandTester->execute([
            'userId' => '12345',
        ]);

        $output = $this->commandTester->getDisplay();
        $this->assertStringContainsString('Unexpected error during sending: Something went wrong', $output);
        $this->assertEquals(1, $this->commandTester->getStatusCode());
    }
}
