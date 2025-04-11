<?php

namespace App\Command;

use App\Repository\User\UserPasswordTokenRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;

#[AsCommand(
    name: 'app:user:delete-password-token',
    description: 'Delete expired password tokens',
)]
#[AutoconfigureTag('console.command')]
class UserDeletePasswordTokenCommand extends Command
{
    public function __construct(
        private readonly UserPasswordTokenRepository $userPasswordTokenRepository,
        private readonly EntityManagerInterface $entityManager,
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $io->title('Delete expired password tokens');

        $passwordTokens = $this->userPasswordTokenRepository->findExpiredTokens();

        $io->info(sprintf('Found %d expired password tokens', count($passwordTokens)));

        foreach ($passwordTokens as $passwordToken) {
            $this->entityManager->remove($passwordToken);
        }

        $this->entityManager->flush();

        $io->success('Expired password tokens have been deleted');

        return Command::SUCCESS;
    }
}
