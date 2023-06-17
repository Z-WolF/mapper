<?php

namespace App\Entity;

use App\Repository\GameRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Blameable\Traits\BlameableEntity;
use Gedmo\Mapping\Annotation\Slug;
use Gedmo\Mapping\Annotation\SoftDeleteable;
use Gedmo\Mapping\Annotation\SortablePosition;
use Gedmo\SoftDeleteable\Traits\SoftDeleteableEntity;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Annotation\Ignore;

#[ORM\Entity(repositoryClass: GameRepository::class)]
#[SoftDeleteable]
class Game
{
    use BlameableEntity;
    use TimestampableEntity;
    use SoftDeleteableEntity;
    use BlameableSoftDeleteTrait;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, unique: true)]
    #[Groups('game:read')]
    private ?string $name = null;

    #[ORM\Column(length: 255, unique: true)]
    #[Groups('game:read')]
    private ?string $shortName = null;

    #[ORM\Column]
    #[SortablePosition]
    private ?int $position = null;

    #[ORM\Column(length: 255, unique: true)]
    #[Slug(fields: ['name'])]
    #[Groups('game:read')]
    private ?string $slug = null;

    #[ORM\Column]
    private ?bool $enabled = null;

    #[ORM\OneToMany(mappedBy: 'game', targetEntity: BaseLayer::class)]
    private Collection $baseLayers;

    #[ORM\OneToOne(mappedBy: 'game', cascade: ['persist', 'remove'])]
    private ?GameConfig $gameConfig = null;

    #[ORM\OneToMany(mappedBy: 'game', targetEntity: FeatureCategory::class, orphanRemoval: true)]
    private Collection $featureCategories;

    public function __construct()
    {
        $this->baseLayers = new ArrayCollection();
        $this->featureCategories = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getShortName(): ?string
    {
        return $this->shortName;
    }

    public function setShortName(string $shortName): self
    {
        $this->shortName = $shortName;

        return $this;
    }

    public function getPosition(): ?int
    {
        return $this->position;
    }

    public function setPosition(int $position): self
    {
        $this->position = $position;

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }

    public function isEnabled(): ?bool
    {
        return $this->enabled;
    }

    public function setEnabled(bool $enabled): self
    {
        $this->enabled = $enabled;

        return $this;
    }

    /**
     * @return Collection<int, BaseLayer>
     */
    public function getBaseLayers(): Collection
    {
        return $this->baseLayers;
    }

    public function addBaseLayer(BaseLayer $baseLayer): self
    {
        if (!$this->baseLayers->contains($baseLayer)) {
            $this->baseLayers->add($baseLayer);
            $baseLayer->setGame($this);
        }

        return $this;
    }

    public function removeBaseLayer(BaseLayer $baseLayer): self
    {
        if ($this->baseLayers->removeElement($baseLayer)) {
            // set the owning side to null (unless already changed)
            if ($baseLayer->getGame() === $this) {
                $baseLayer->setGame(null);
            }
        }

        return $this;
    }

    public function getGameConfig(): ?GameConfig
    {
        return $this->gameConfig;
    }

    public function setGameConfig(GameConfig $gameConfig): self
    {
        // set the owning side of the relation if necessary
        if ($gameConfig->getGame() !== $this) {
            $gameConfig->setGame($this);
        }

        $this->gameConfig = $gameConfig;

        return $this;
    }

    public function __toString(): string
    {
        return $this->getName();
    }

    /**
     * @return Collection<int, FeatureCategory>
     */
    public function getFeatureCategories(): Collection
    {
        return $this->featureCategories;
    }

    public function addFeatureCategory(FeatureCategory $featureCategory): static
    {
        if (!$this->featureCategories->contains($featureCategory)) {
            $this->featureCategories->add($featureCategory);
            $featureCategory->setGame($this);
        }

        return $this;
    }

    public function removeFeatureCategory(FeatureCategory $featureCategory): static
    {
        if ($this->featureCategories->removeElement($featureCategory)) {
            // set the owning side to null (unless already changed)
            if ($featureCategory->getGame() === $this) {
                $featureCategory->setGame(null);
            }
        }

        return $this;
    }
}
