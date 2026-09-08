<?php

namespace App\Entity;

use App\Entity\File\FileObject;
use App\Repository\ProjectResourceRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Serializer\Attribute\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Ressource (fichier) associée à un projet.
 */
#[ORM\Entity(repositoryClass: ProjectResourceRepository::class)]
#[ORM\Table(name: 'project_resource')]
class ProjectResource
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups([Project::GET_FULL])]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: Project::class, inversedBy: 'resources')]
    #[ORM\JoinColumn(name: 'project_id', referencedColumnName: 'id', nullable: false, onDelete: 'CASCADE')]
    private ?Project $project = null;

    #[ORM\ManyToOne(targetEntity: FileObject::class)]
    #[ORM\JoinColumn(name: 'file_object_id', referencedColumnName: 'id', nullable: false, onDelete: 'CASCADE')]
    #[Assert\NotNull]
    #[Groups([Project::GET_FULL, Project::WRITE])]
    private ?FileObject $fileObject = null;

    #[Gedmo\Timestampable(on: 'create')]
    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    #[Groups([Project::GET_FULL])]
    private ?\DateTimeInterface $createdAt = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getProject(): ?Project
    {
        return $this->project;
    }

    public function setProject(?Project $project): static
    {
        $this->project = $project;

        return $this;
    }

    public function getFileObject(): ?FileObject
    {
        return $this->fileObject;
    }

    public function setFileObject(?FileObject $fileObject): static
    {
        $this->fileObject = $fileObject;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }
}