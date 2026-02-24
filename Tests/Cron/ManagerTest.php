<?php declare(strict_types=1);
/**
 * This file is part of the SymfonyCronBundle package.
 *
 * (c) Dries De Peuter <dries@nousefreak.be>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cron\CronBundle\Tests\Cron;

use Cron\CronBundle\Cron\Manager;
use Cron\CronBundle\Entity\CronJob;
use Cron\CronBundle\Job\ShellJobWrapper;
use Cron\Report\JobReport;
use Doctrine\ORM\EntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Persistence\ObjectManager;
use Exception;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 * @author Dries De Peuter <dries@nousefreak.be>
 */
class ManagerTest extends TestCase
{
    public function testListJobs(): void
    {
        $jobRepo = $this->buildRepo();
        $jobRepo
            ->expects($this->once())
            ->method('findBy')
            ->with([], ['name' => 'asc'], null, null)
            ->willReturn(['listJobsResult']);

        $manager = $this->getManagerForRepo($jobRepo);
        $this->assertSame(['listJobsResult'], $manager->listJobs());
    }

    public function testListEnabledJobs(): void
    {
        $jobRepo = $this->buildRepo();
        $jobRepo
            ->expects($this->once())
            ->method('findBy')
            ->with(['enabled' => 1], ['name' => 'asc'], null, null)
            ->willReturn(['listEnabledJobsResult']);

        $manager = $this->getManagerForRepo($jobRepo);
        $this->assertSame(['listEnabledJobsResult'], $manager->listEnabledJobs());
    }

    public function testGetJobByName(): void
    {
        $jobName = 'testJobName';
        $job = new CronJob();

        $jobRepo = $this->buildRepo();
        $jobRepo
            ->expects($this->once())
            ->method('findOneBy')
            ->with(['name' => $jobName], null)
            ->willReturn($job);

        $manager = $this->getManagerForRepo($jobRepo);
        $this->assertSame($job, $manager->getJobByName($jobName));
    }

    /**
     * @throws Exception
     */
    public function testSaveReportsEmpty()
    {
        $entityManager = $this->buildEm();
        $entityManager
            ->expects($this->once())
            ->method('flush');

        $registry = $this->buildRegistry();
        $registry
            ->expects($this->once())
            ->method('getManagerForClass')
            ->willReturn($entityManager);

        $manager = $this->getManager($registry);

        $manager->saveReports([]);
    }

    /**
     * @throws Exception
     */
    public function testSaveReports()
    {
        $entityManager = $this->buildEm();
        $entityManager
            ->expects($this->once())
            ->method('flush');
        $entityManager
            ->expects($this->once())
            ->method('persist');

        $registry = $this->buildRegistry();
        $registry
            ->expects($this->once())
            ->method('getManagerForClass')
            ->willReturn($entityManager);

        $manager = $this->getManager($registry);

        $job = new ShellJobWrapper();
        $job->setCommand('ls');

        $report = $this->getMockBuilder(JobReport::class)
            ->setConstructorArgs([$job])
            ->getMock();

        $report->expects($this->any())
            ->method('getJob')
            ->willReturn($job);
        $report->expects($this->exactly(2))
            ->method('getStartTime');
        $report->expects($this->once())
            ->method('getEndTime');

        $manager->saveReports([$report]);
    }

    public function testDeleteJob()
    {
        $entityManager = $this->buildEm();
        $entityManager
            ->expects($this->once())
            ->method('flush');
        $entityManager
            ->expects($this->once())
            ->method('remove');

        $registry = $this->buildRegistry();
        $registry
            ->expects($this->once())
            ->method('getManagerForClass')
            ->willReturn($entityManager);

        $manager = $this->getManager($registry);

        $manager->deleteJob(new CronJob());
    }

    public function testSaveJob()
    {
        $entityManager = $this->buildEm();
        $entityManager
            ->expects($this->once())
            ->method('flush');
        $entityManager
            ->expects($this->once())
            ->method('persist');

        $registry = $this->buildRegistry();
        $registry
            ->expects($this->once())
            ->method('getManagerForClass')
            ->willReturn($entityManager);

        $manager = $this->getManager($registry);

        $manager->saveJob(new CronJob());
    }

    protected function getManagerForRepo(MockObject $jobRepo): Manager
    {
        $entityManager = $this->buildEm();
        $entityManager
            ->expects($this->once())
            ->method('getRepository')
            ->willReturn($jobRepo);

        $registry = $this->buildRegistry();
        $registry
            ->expects($this->once())
            ->method('getManagerForClass')
            ->willReturn($entityManager);

        return $this->getManager($registry);
    }

    protected function buildRepo(): MockObject
    {
        return $this->getMockBuilder(EntityRepository::class)
            ->disableOriginalConstructor()
            ->getMock();
    }

    protected function buildEm(): MockObject
    {
        return $this->getMockBuilder(ObjectManager::class)
            ->disableOriginalConstructor()
            ->getMock();
    }

    protected function buildRegistry(): ManagerRegistry&MockObject
    {
        return $this->getMockBuilder(ManagerRegistry::class)
            ->disableOriginalConstructor()
            ->getMock();
    }

    protected function getManager(ManagerRegistry $registry): Manager
    {
        return new Manager($registry);
    }
}
