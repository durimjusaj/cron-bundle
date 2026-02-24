<?php declare(strict_types=1);

namespace Cron\CronBundle\Entity;

use Cron\CronBundle\Repository\CronJobRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

#[ORM\Entity(repositoryClass: CronJobRepository::class)]
#[ORM\Table(name: 'cron_job', uniqueConstraints: [new ORM\UniqueConstraint(name: 'un_name', columns: ['name'])])]
class CronJob
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'AUTO')]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\Column(type: 'string', length: 191)]
    private ?string $name = null;

    #[ORM\Column(type: 'string', length: 1024)]
    private ?string $command = null;

    #[ORM\Column(type: 'string', length: 191)]
    private ?string $schedule = null;

    #[ORM\Column(type: 'string', length: 191)]
    private ?string $description = null;

    #[ORM\Column(type: 'boolean', nullable: true)]
    private ?bool $enabled = null;

    #[ORM\OneToMany(targetEntity: CronReport::class, mappedBy: 'job', cascade: ['remove'])]
    protected Collection $reports;

    public function __construct()
    {
        $this->reports = new ArrayCollection();
    }

    /**
     * Get id
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Set name
     */
    public function setName(?string $name): static
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * Set command
     */
    public function setCommand(?string $command): static
    {
        $this->command = $command;

        return $this;
    }

    /**
     * Get command
     */
    public function getCommand(): ?string
    {
        return $this->command;
    }

    /**
     * Set schedule
     */
    public function setSchedule(?string $schedule): static
    {
        $this->schedule = $schedule;

        return $this;
    }

    /**
     * Get schedule
     */
    public function getSchedule(): ?string
    {
        return $this->schedule;
    }

    /**
     * Set description
     */
    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * Set enabled
     */
    public function setEnabled(?bool $enabled): static
    {
        $this->enabled = $enabled;

        return $this;
    }

    /**
     * Get enabled
     */
    public function getEnabled(): ?bool
    {
        return $this->enabled;
    }

    public function getReports(): Collection
    {
        return $this->reports;
    }

    public function __toString()
    {
        return $this->name;
    }
}
