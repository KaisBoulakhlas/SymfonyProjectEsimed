<?php


namespace App\Command;


use App\Repository\AdvertRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;


class DeleteAdvertsRejected extends Command
{
    private AdvertRepository $repository;

    private EntityManagerInterface $manager;

    public function __construct(AdvertRepository $repository, EntityManagerInterface $manager)
    {
        parent::__construct();
        $this->repository = $repository;
        $this->manager    = $manager;

    }
    protected function configure()
    {
        $this
            ->setName("app:delete:adverts:rejected")
            ->setDescription('Supprime chaque annonces rejetées')
            ->addArgument('days', InputArgument::REQUIRED, 'Date de création');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $io->title('Delete adverts');

        $days = $input->getArgument('days');


        $adverts =  $this->repository
            ->createQueryBuilder('advert')
            ->andWhere('advert.createdAt > :date')
            ->andWhere('advert.state :rejected')
            ->setParameter('date', (new DateTime())->sub(\DateInterval::createFromDateString($days.'days')))
            ->setParameter('rejected', 'rejected')
            ->getQuery()
            ->getResult();

        $io->progressStart(\count($adverts));

        foreach($adverts as $advert){
            $this->manager->remove($advert);
        }

        $this->manager->flush();

        $io->success('Annonces supprimées');

        return Command::SUCCESS;
    }
}