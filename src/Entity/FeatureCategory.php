<?php

namespace App\Entity;

use App\Repository\FeatureCategoryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Blameable\Traits\BlameableEntity;
use Gedmo\Mapping\Annotation\SoftDeleteable;
use Gedmo\Mapping\Annotation\SortableGroup;
use Gedmo\Mapping\Annotation\SortablePosition;
use Gedmo\SoftDeleteable\Traits\SoftDeleteableEntity;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: FeatureCategoryRepository::class)]
#[SoftDeleteable]
class FeatureCategory
{
    use BlameableEntity;
    use TimestampableEntity;
    use SoftDeleteableEntity;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['category:read', 'category:read:tree'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups('category:read')]
    private ?string $name = null;

    #[ORM\Column]
    #[Groups('category:read')]
    private ?int $minZoom = null;

    #[ORM\Column]
    #[Groups('category:read')]
    private ?int $maxZoom = 8;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups('category:read')]
    private ?string $color = null;

    #[ORM\Column(options: ['default' => true])]
    private ?bool $enabled = true;

    #[ORM\Column(options: ['default' => false])]
    #[Groups('category:read')]
    private ?bool $label = false;

    #[ORM\ManyToOne(targetEntity: self::class, inversedBy: 'children')]
    #[SortableGroup]
    private ?self $parent = null;

    #[ORM\OneToMany(mappedBy: 'parent', targetEntity: self::class)]
    #[Groups('category:read:tree')]
    private Collection $children;

    #[ORM\Column]
    #[SortablePosition]
    private ?int $position = null;

    #[ORM\ManyToOne(inversedBy: 'featureCategories')]
    #[ORM\JoinColumn(nullable: false)]
    #[SortableGroup]
    private ?Game $game = null;

    #[ORM\Column(nullable: true)]
    private ?int $oldId = null;

    #[ORM\OneToMany(mappedBy: 'category', targetEntity: Feature::class, orphanRemoval: true)]
    private Collection $features;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups('category:read')]
    private ?string $iconClass = null;

    public function __construct()
    {
        $this->children = new ArrayCollection();
        $this->features = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getMinZoom(): ?int
    {
        return $this->minZoom;
    }

    public function setMinZoom(int $minZoom): static
    {
        $this->minZoom = $minZoom;

        return $this;
    }

    public function getMaxZoom(): ?int
    {
        return $this->maxZoom;
    }

    public function setMaxZoom(int $maxZoom): static
    {
        $this->maxZoom = $maxZoom;

        return $this;
    }

    public function getColor(): ?string
    {
        return $this->color;
    }

    public function setColor(?string $color): static
    {
        $this->color = $color;

        return $this;
    }

    public function isEnabled(): ?bool
    {
        return $this->enabled;
    }

    public function setEnabled(bool $enabled): static
    {
        $this->enabled = $enabled;

        return $this;
    }

    public function isLabel(): ?bool
    {
        return $this->label;
    }

    public function setLabel(bool $label): static
    {
        $this->label = $label;

        return $this;
    }

    public function getParent(): ?self
    {
        return $this->parent;
    }

    public function setParent(?self $parent): static
    {
        $this->parent = $parent;

        return $this;
    }

    /**
     * @return Collection<int, self>
     */
    public function getChildren(): Collection
    {
        return $this->children;
    }

    public function addChild(self $child): static
    {
        if (!$this->children->contains($child)) {
            $this->children->add($child);
            $child->setParent($this);
        }

        return $this;
    }

    public function removeChild(self $child): static
    {
        if ($this->children->removeElement($child)) {
            // set the owning side to null (unless already changed)
            if ($child->getParent() === $this) {
                $child->setParent(null);
            }
        }

        return $this;
    }

    public function getPosition(): ?int
    {
        return $this->position;
    }

    public function setPosition(int $position): static
    {
        $this->position = $position;

        return $this;
    }

    public function getGame(): ?Game
    {
        return $this->game;
    }

    public function setGame(?Game $game): static
    {
        $this->game = $game;

        return $this;
    }

    public function getOldId(): ?int
    {
        return $this->oldId;
    }

    public function setOldId(?int $oldId): static
    {
        $this->oldId = $oldId;

        return $this;
    }

    /**
     * @return Collection<int, Feature>
     */
    public function getFeatures(): Collection
    {
        return $this->features;
    }

    public function addFeature(Feature $feature): static
    {
        if (!$this->features->contains($feature)) {
            $this->features->add($feature);
            $feature->setCategory($this);
        }

        return $this;
    }

    public function removeFeature(Feature $feature): static
    {
        if ($this->features->removeElement($feature)) {
            // set the owning side to null (unless already changed)
            if ($feature->getCategory() === $this) {
                $feature->setCategory(null);
            }
        }

        return $this;
    }

    public function __toString(): string
    {
        return sprintf('%s (%s)', $this->getName(), $this->getGame()->getShortName());
    }

    public function getIconClass(): ?string
    {
        return $this->iconClass;
    }

    public function setIconClass(?string $iconClass): static
    {
        $this->iconClass = $iconClass;

        return $this;
    }
}
