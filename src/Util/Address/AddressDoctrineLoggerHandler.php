<?php
namespace App\Util\Address;

use App\Entity\Address\Log;
use App\Repository\Address\LogRepository;
use Doctrine\ORM\EntityManagerInterface;
use Monolog\Handler\AbstractProcessingHandler;

class AddressDoctrineLoggerHandler extends AbstractProcessingHandler
{
    /**
     * @var EntityManagerInterface
     */
    private EntityManagerInterface $entityManager;

    /**
     * @var LogRepository
     */
    private LogRepository $logRepository;

    /**
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager, LogRepository $logRepository)
    {
        parent::__construct();
        $this->entityManager = $entityManager;
        $this->logRepository = $logRepository;
    }

    /**
     * @param array $record
     */
    protected function write(array $record): void
    {
        $log = new Log();
        $log->setMessage($record['message']);
        $log->setIp($record['context']['ip']);

        $this->logRepository->persistLog($log);
    }
}