<?php

namespace App\Entity;

use App\Enums\Serializer\AdviceEnum;
use App\Repository\AdviceRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: AdviceRepository::class)]
class Advice
{
    /**
     * The unique identifier for the advice.
     * @var int|null
     */
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups([AdviceEnum::ADVICE_LIST->value, AdviceEnum::ADVICE_SHOW->value])]
    private ?int $id = null;

    /**
     * The title of the advice.
     * @var string|null
     */
    #[ORM\Column(length: 48)]
    #[Groups([AdviceEnum::ADVICE_LIST->value, AdviceEnum::ADVICE_SHOW->value])]
    private ?string $title = null;

    /**
     * A detailed description of the advice.
     * @var string|null
     */
    #[ORM\Column(type: Types::TEXT)]
    #[Groups([AdviceEnum::ADVICE_LIST->value, AdviceEnum::ADVICE_SHOW->value])]
    private ?string $description = null;

    /**
     * An array of months (as strings) during which the advice is applicable.
     * @var array|null
     */
    #[ORM\Column(type: Types::JSON, nullable: true)]
    #[Groups([AdviceEnum::ADVICE_LIST->value, AdviceEnum::ADVICE_SHOW->value])]
    private ?array $months = null;

    /**
     * The date and time when the advice was created.
     * @var \DateTimeImmutable|null
     */
    #[ORM\Column]
    #[Groups([AdviceEnum::ADVICE_LIST->value, AdviceEnum::ADVICE_SHOW->value])]
    private ?\DateTimeImmutable $created_at = null;

    /**
     * The date and time when the advice was last updated.
     * @var \DateTimeImmutable|null
     */
    #[ORM\Column(nullable: true)]
    #[Groups([AdviceEnum::ADVICE_LIST->value, AdviceEnum::ADVICE_SHOW->value])]
    private ?\DateTimeImmutable $updated_at = null;

    /**
     * Get the value of id
     *
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Get the value of title
     *
     * @return string|null
     */
    public function getTitle(): ?string
    {
        return $this->title;
    }

    /**
     * Set the value of title
     *
     * @param string $title
     *
     * @return $this
     */
    public function setTitle(string $title): static
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get the value of description
     *
     * @return string|null
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * Set the value of description
     *
     * @param string $description
     *
     * @return $this
     */
    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get the value of months
     *
     * @return array|null
     */
    public function getMonths(): ?array
    {
        return $this->months;
    }

    /**
     * Set the value of months
     *
     * @param array|null $months
     * @return static
     */
    public function setMonths(?array $months): static
    {
        $this->months = $months;

        return $this;
    }

    /**
     * Get the value of created_at
     *
     * @return \DateTimeImmutable|null
     */
    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->created_at;
    }

    /**
     * Set the value of created_at
     *
     * @param \DateTimeImmutable $created_at
     * @return static
     */
    public function setCreatedAt(\DateTimeImmutable $created_at): static
    {
        $this->created_at = $created_at;

        return $this;
    }

    /**
     * Get the value of updated_at
     *
     * @return \DateTimeImmutable|null
     */
    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updated_at;
    }

    /**
     * Set the value of updated_at
     *
     * @param \DateTimeImmutable|null $updated_at
     * @return static
     */
    public function setUpdatedAt(?\DateTimeImmutable $updated_at): static
    {
        $this->updated_at = $updated_at;

        return $this;
    }
}
