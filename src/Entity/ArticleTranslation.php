<?php

namespace App\Entity;

use App\Repository\ArticleTranslationRepository;
use Doctrine\ORM\Mapping as ORM;
use Knp\DoctrineBehaviors\Contract\Entity\TranslationInterface;
use Knp\DoctrineBehaviors\Model\Translatable\TranslationTrait;
use JMS\Serializer\Annotation as Serializer;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ArticleTranslationRepository::class)]
#[Serializer\ExclusionPolicy('all')]
class ArticleTranslation implements TranslationInterface
{
    use TranslationTrait;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Serializer\Expose()]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Serializer\Expose()]
    #[Assert\NotBlank()]
    private ?string $title = null;

    #[ORM\Column(type: 'text', nullable: true)]
    #[Serializer\Expose()]
    #[Assert\NotBlank()]
    private ?string $description = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(?string $title): self
    {
        $this->title = $title;
        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;
        return $this;
    }
}
