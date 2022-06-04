<?php
namespace App\Util\Address;

use App\Entity\Address\Log;
use Doctrine\ORM\EntityManagerInterface;
use Monolog\Handler\AbstractProcessingHandler;

class AddressDoctrineLoggerHandler extends AbstractProcessingHandler
{
    /**
     * @var EntityManagerInterface
     */
    private EntityManagerInterface $entityManager;

    /**
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        parent::__construct();
        $this->entityManager = $entityManager;
    }

    /**
     * @param array $record
     */
    protected function write(array $record): void
    {
        $logEntry = new Log();
        $logEntry->setMessage($record['message']);
        $logEntry->setIp($record['context']['ip']);

        $this->entityManager->persist($logEntry);
        $this->entityManager->flush();
    }
}