<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

trait BlameableSoftDeleteTrait
{
    #[ORM\Column(nullable: true)]
    protected ?string $deletedBy;

    public function getDeletedBy(): ?string
    {
        return $this->deletedBy;
    }

    public function setDeletedBy(?string $deletedBy): self
    {
        $this->deletedBy = $deletedBy;

        return $this;
    }
}