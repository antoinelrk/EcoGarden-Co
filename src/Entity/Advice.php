<?php

namespace App\Entity;

use App\Enums\Serializer\AdviceEnum;
use App\Repository\AdviceRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation\Groups;
use phpDocumentor\Reflection\Types\Integer;

#[ORM\Entity(repositoryClass: AdviceRepository::class)]
class Advice
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups([AdviceEnum::ADVICE_LIST->value, AdviceEnum::ADVICE_SHOW->value])]
    private ?int $id = null;

    #[ORM\Column(length: 48)]
    #[Groups([AdviceEnum::ADVICE_LIST->value, AdviceEnum::ADVICE_SHOW->value])]
    private ?string $title = null;

    #[ORM\Column(type: Types::TEXT)]
    #[Groups([AdviceEnum::ADVICE_LIST->value, AdviceEnum::ADVICE_SHOW->value])]
    private ?string $description = null;

    #[ORM\Column(type: Types::JSON, nullable: true)]
    #[Groups([AdviceEnum::ADVICE_LIST->value, AdviceEnum::ADVICE_SHOW->value])]
    private ?array $months = null;

    #[ORM\Column]
    #[Groups([AdviceEnum::ADVICE_LIST->value, AdviceEnum::ADVICE_SHOW->value])]
    private ?\DateTimeImmutable $created_at = null;

    #[ORM\Column(nullable: true)]
    #[Groups([AdviceEnum::ADVICE_LIST->value, AdviceEnum::ADVICE_SHOW->value])]
    private ?\DateTimeImmutable $updated_at = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getMonths(): ?array
    {
        return $this->months;
    }

    public function setMonths(?array $months): static
    {
        $this->months = $months;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTimeImmutable $created_at): static
    {
        $this->created_at = $created_at;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updated_at;
    }

    public function setUpdatedAt(?\DateTimeImmutable $updated_at): static
    {
        $this->updated_at = $updated_at;

        return $this;
    }
}
