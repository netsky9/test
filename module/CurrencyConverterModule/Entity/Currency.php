<?php

namespace Module\CurrencyConverterModule\Entity;

use App\Entity\BaseEntity;
use Doctrine\ORM\Mapping as ORM;
use Module\CurrencyConverterModule\Repository\CurrencyRepository;

/**
 * @ORM\HasLifecycleCallbacks()
 * @ORM\Entity(repositoryClass=CurrencyRepository::class)
 */
class Currency extends BaseEntity
{
    /**
     * @ORM\Column(type="string", length=3, unique=true)
     */
    private $code;

    /**
     * @ORM\Column(type="decimal", precision=20, scale=10)
     */
    private $value;

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(string $code): self
    {
        $this->code = $code;

        return $this;
    }

    public function getValue(): ?string
    {
        return $this->value;
    }

    public function setValue(string $value): self
    {
        $this->value = $value;

        return $this;
    }
}
