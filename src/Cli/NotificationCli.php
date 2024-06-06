<?php

namespace App\Cli;

use App\Domain\Handler\CliHandlerInterface;
use Exception;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

final class NotificationCli extends Command
{
    private CliHandlerInterface $cliHandler;

    public function __construct(CliHandlerInterface $cliHandler)
    {
        parent::__construct();
        $this->cliHandler = $cliHandler;
    }

    protected function configure(): void
    {
        $this
            ->setName('send:delayed')
            ->setDescription('This command tries to resend messages')
            ->addArgument('userId', InputArgument::REQUIRED, 'UserId for delayed messages');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $userId = $input->getArgument('userId');

        try {
            $this->cliHandler->handle($userId);
            $io->success('Messages have been successfully sent.');

            return Command::SUCCESS;
        } catch (Exception $e) {
            $io->error('Unexpected error during sending: ' . $e->getMessage());

            return Command::FAILURE;
        }
    }
}
