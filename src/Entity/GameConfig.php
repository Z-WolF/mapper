<?php

namespace App\Entity;

use App\Repository\GameConfigRepository;
use Brick\Geo\IO\GeoJSONWriter;
use Brick\Geo\Point;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Context;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Annotation\Ignore;
use Symfony\Component\Serializer\Annotation\MaxDepth;
use Symfony\Component\Serializer\Annotation\SerializedName;
use Symfony\Component\Serializer\Context\Normalizer\ObjectNormalizerContextBuilder;

#[ORM\Entity(repositoryClass: GameConfigRepository::class)]
class GameConfig
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\OneToOne(inversedBy: 'gameConfig', cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?Game $game = null;

    #[ORM\OneToOne]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups('config:read')]
    private ?BaseLayer $defaultBaseLayer = null;

    #[ORM\Column]
    #[Groups('config:read')]
    private ?int $defaultZoom = null;

    #[ORM\Column(nullable: true)]
    #[Groups('config:read')]
    private ?int $minZoom = null;

    #[ORM\Column(nullable: true)]
    #[Groups('config:read')]
    private ?int $maxZoom = null;

    #[ORM\Column(type: 'geometry', options: ['geometry_type' => 'POINT'])]
    private ?string $defaultCenter = null;

    #[ORM\Column(nullable: true)]
    #[Groups('config:read')]
    private ?float $scaleX = null;

    #[ORM\Column(nullable: true)]
    #[Groups('config:read')]
    private ?float $scaleY = null;

    #[ORM\Column(nullable: true)]
    #[Groups('config:read')]
    private ?float $offsetX = null;

    #[ORM\Column(nullable: true)]
    #[Groups('config:read')]
    private ?float $offsetY = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getGame(): ?Game
    {
        return $this->game;
    }

    public function setGame(Game $game): self
    {
        $this->game = $game;

        return $this;
    }

    public function getDefaultBaseLayer(): ?BaseLayer
    {
        return $this->defaultBaseLayer;
    }

    public function setDefaultBaseLayer(BaseLayer $defaultBaseLayer): self
    {
        $this->defaultBaseLayer = $defaultBaseLayer;

        return $this;
    }

    public function getDefaultZoom(): ?int
    {
        return $this->defaultZoom;
    }

    public function setDefaultZoom(int $defaultZoom): self
    {
        $this->defaultZoom = $defaultZoom;

        return $this;
    }

    public function getMinZoom(): ?int
    {
        return $this->minZoom;
    }

    public function setMinZoom(?int $minZoom): self
    {
        $this->minZoom = $minZoom;

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

    public function getDefaultCenter(): ?string
    {
        return $this->defaultCenter;
    }

    public function setDefaultCenter(?string $defaultCenter): self
    {
        $this->defaultCenter = $defaultCenter;

        return $this;
    }

    public function __toString(): string
    {
        return $this->getGame()->getShortName();
    }

    #[Groups('config:read')]
    #[SerializedName('defaultCenter')]
    public function getDefaultCenterArray()
    {
        return !is_null($this->getDefaultCenter()) ? Point::fromText($this->getDefaultCenter())->toArray() : null;
    }

    public function getScaleX(): ?float
    {
        return $this->scaleX;
    }

    public function setScaleX(?float $scaleX): static
    {
        $this->scaleX = $scaleX;

        return $this;
    }

    public function getScaleY(): ?float
    {
        return $this->scaleY;
    }

    public function setScaleY(?float $scaleY): static
    {
        $this->scaleY = $scaleY;

        return $this;
    }

    public function getOffsetX(): ?float
    {
        return $this->offsetX;
    }

    public function setOffsetX(?float $offsetX): static
    {
        $this->offsetX = $offsetX;

        return $this;
    }

    public function getOffsetY(): ?float
    {
        return $this->offsetY;
    }

    public function setOffsetY(?float $offsetY): static
    {
        $this->offsetY = $offsetY;

        return $this;
    }
}
