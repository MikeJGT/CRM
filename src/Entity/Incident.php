<?php

namespace App\Entity;

use App\Repository\IncidentRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints\Date;

#[ORM\Entity(repositoryClass: IncidentRepository::class)]
class Incident
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $Description = null;

    #[ORM\Column(length: 255)]
    private ?string $State = null;

    #[ORM\Column(length: 255)]
    private ?string $Priority = null;

    #[ORM\Column(length: 255)]
    private ?string $Assigned = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $Observations = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $startDate = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $finishDate = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $brochureFilename = null;

    #[ORM\OneToMany(mappedBy: 'incident', targetEntity: Brochure::class, orphanRemoval: true)]
    private Collection $brochures;

    public function __construct()
    {
        $this->brochures = new ArrayCollection();
    }

    // Upload File
    // Guardar varios archivos setBrouchure to [].
    // Nuva tabla de datos para guardar la referencia de los archivos.

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDescription(): ?string
    {
        return $this->Description;
    }

    public function setDescription(string $Description): static
    {
        $this->Description = $Description;

        return $this;
    }

    public function getState(): ?string
    {
        return $this->State;
    }

    public function setState(string $State): static
    {
        $this->State = $State;

        return $this;
    }

    public function getPriority(): ?string
    {
        return $this->Priority;
    }

    public function setPriority(string $Priority): static
    {
        $this->Priority = $Priority;

        return $this;
    }

    public function getAssigned(): ?string
    {
        return $this->Assigned;
    }

    public function setAssigned(string $Assigned): string
    {
        $this->Assigned = $Assigned;

        return $this->Assigned;
    }

    public function getObservations(): ?string
    {
        return $this->Observations;
    }

    public function setObservations(string $Observations): static
    {
        $this->Observations = $Observations;

        return $this;
    }

    public function getStartDate(): ?\DateTimeInterface
    {
        return $this->startDate;
    }

    public function setStartDate(\DateTimeInterface $startDate): static
    {
        $this->startDate = $startDate;

        return $this;
    }

    public function getFinishDate(): ?\DateTimeInterface
    {
        return $this->finishDate;
    }

    public function setFinishDate(\DateTimeInterface $finishDate=null): static
    {
        $this->finishDate = $finishDate;

        return $this;
    }

    public function getBrochureFilename(): ?string
    {
        return $this->brochureFilename;
    }

    public function setBrochureFilename(?string $brochureFilename): static
    {
        $this->brochureFilename = $brochureFilename;

        return $this;
    }

    /**
     * @return Collection<int, Brochure>
     */
    public function getBrochures(): Collection
    {
        return $this->brochures;
    }

    public function addBrochure(Brochure $brochure): static
    {
        if (!$this->brochures->contains($brochure)) {
            $this->brochures->add($brochure);
            $brochure->setIncident($this);
        }

        return $this;
    }

    public function removeBrochure(Brochure $brochure): static
    {
        if ($this->brochures->removeElement($brochure)) {
            // set the owning side to null (unless already changed)
            if ($brochure->getIncident() === $this) {
                $brochure->setIncident(null);
            }
        }

        return $this;
    }

}
