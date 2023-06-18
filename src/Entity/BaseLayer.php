<?php

namespace App\Entity;

use App\Repository\BaseLayerRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Blameable\Traits\BlameableEntity;
use Gedmo\Mapping\Annotation\SoftDeleteable;
use Gedmo\Mapping\Annotation\SortableGroup;
use Gedmo\Mapping\Annotation\SortablePosition;
use Gedmo\SoftDeleteable\Traits\SoftDeleteableEntity;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: BaseLayerRepository::class)]
#[SoftDeleteable]
class BaseLayer
{
    use BlameableEntity;
    use TimestampableEntity;
    use SoftDeleteableEntity;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['config:read', 'layer:read'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups('layer:read')]
    private ?string $name = null;

    #[ORM\Column(type: Types::TEXT)]
    #[Groups('layer:read')]
    private ?string $url = null;

    #[ORM\Column(nullable: true)]
    #[Groups('layer:read')]
    private ?int $maxZoom = null;

    #[ORM\ManyToOne(inversedBy: 'baseLayers')]
    #[ORM\JoinColumn(nullable: false)]
    #[SortableGroup]
    private ?Game $game = null;

    #[ORM\Column]
    #[SortablePosition]
    private ?int $position = null;

    #[ORM\Column(options: ['default' => false])]
    private ?bool $enabled = false;

    #[ORM\OneToMany(mappedBy: 'baseLayer', targetEntity: Feature::class)]
    private Collection $features;

    public function __construct()
    {
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

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getUrl(): ?string
    {
        return $this->url;
    }

    public function setUrl(string $url): self
    {
        $this->url = $url;

        return $this;
    }

    public function getMaxZoom(): ?int
    {
        return $this->maxZoom;
    }

    public function setMaxZoom(?int $maxZoom): self
    {
        $this->maxZoom = $maxZoom;

        return $this;
    }

    public function getGame(): ?Game
    {
        return $this->game;
    }

    public function setGame(?Game $game): self
    {
        $this->game = $game;

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
            $feature->setBaseLayer($this);
        }

        return $this;
    }

    public function removeFeature(Feature $feature): static
    {
        if ($this->features->removeElement($feature)) {
            // set the owning side to null (unless already changed)
            if ($feature->getBaseLayer() === $this) {
                $feature->setBaseLayer(null);
            }
        }

        return $this;
    }

    public function __toString(): string
    {
        return sprintf('%s (%s)', $this->getName(), $this->getGame()->getShortName());
    }
}
