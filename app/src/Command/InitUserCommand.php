<?php
namespace App\Command;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;


// the name of the command is what users type after "php bin/console"
#[AsCommand(name: 'app:init-user')]
class InitUserCommand extends Command
{
    public function __construct(
        protected EntityManagerInterface $em,
        protected UserPasswordHasherInterface $passwordHasher
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $userEmail = 'test@domain.com';
        $plaintextPassword = 'passasd';
        $user = $this->em->getRepository(User::class)->findOneBy(['email' => $userEmail]);

        if (!$user) {
            $user = new User();

            $user->setEmail($userEmail);

            $hashedPassword = $this->passwordHasher->hashPassword(
                $user,
                $plaintextPassword
            );
            $user->setPassword($hashedPassword);

            $this->em->persist($user);
            $this->em->flush();

        }
        // this method must return an integer number with the "exit status code"
        // of the command. You can also use these constants to make code more readable

        // return this if there was no problem running the command
        // (it's equivalent to returning int(0))
        return Command::SUCCESS;


    }
}