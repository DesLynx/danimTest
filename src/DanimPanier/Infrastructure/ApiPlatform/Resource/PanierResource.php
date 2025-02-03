<?php

declare(strict_types=1);

namespace App\DanimPanier\Infrastructure\ApiPlatform\Resource;

use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Put;
use App\DanimPanier\Domain\Event\PanierEvent;
use App\DanimPanier\Domain\Model\Panier;
use App\DanimPanier\Infrastructure\ApiPlatform\Payload\DiscountPanierPayload;
use App\DanimPanier\Infrastructure\ApiPlatform\State\Processor\CreatePanierProcessor;
use App\DanimPanier\Infrastructure\ApiPlatform\State\Processor\DeletePanierProcessor;
use App\DanimPanier\Infrastructure\ApiPlatform\State\Processor\DiscountPanierProcessor;
use App\DanimPanier\Infrastructure\ApiPlatform\State\Processor\UpdatePanierProcessor;
use App\DanimPanier\Infrastructure\ApiPlatform\State\Provider\PanierCollectionProvider;
use App\DanimPanier\Infrastructure\ApiPlatform\State\Provider\PanierEventsProvider;
use App\DanimPanier\Infrastructure\ApiPlatform\State\Provider\PanierItemProvider;
use Symfony\Component\Serializer\Attribute\Groups;
use Symfony\Component\Uid\AbstractUid;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Validator\Constraints as Assert;

#[ApiResource(
    shortName: 'Panier',
    operations: [
        // queries
        new GetCollection(
            '/paniers/{id}/events.{_format}',
            openapiContext: ['summary' => 'Get events for this Panier.'],
            normalizationContext: ['groups' => []],
            output: PanierEvent::class,
            provider: PanierEventsProvider::class,
        ),

        // commands
        new Post(
            '/paniers/{id}/coupon.{_format}',
            openapiContext: ['summary' => 'Apply a Coupon on a Panier resource.'],
            denormalizationContext: ['groups' => []],
            input: DiscountPanierPayload::class,
            provider: PanierItemProvider::class,
            processor: DiscountPanierProcessor::class,
        ),

        // basic crud
        new GetCollection(
            provider: PanierCollectionProvider::class,
        ),
        new Get(
            provider: PanierItemProvider::class,
        ),
        new Post(
            validationContext: ['groups' => ['create']],
            processor: CreatePanierProcessor::class,
        ),
        new Put(
            provider: PanierItemProvider::class,
            processor: UpdatePanierProcessor::class,
            extraProperties: ['standard_put' => true],
        ),
        new Patch(
            provider: PanierItemProvider::class,
            processor: UpdatePanierProcessor::class,
        ),
        new Delete(
            provider: PanierItemProvider::class,
            processor: DeletePanierProcessor::class,
        ),
    ],
    normalizationContext: ['groups' => ['read:panier']],
    denormalizationContext: ['groups' => ['write:panier']],
)]
final class PanierResource
{
    public function __construct(
        #[ApiProperty(identifier: true, readable: false, writable: false)]
        public ?AbstractUid $id = null,

        #[Assert\NotNull(groups: ['create'])]
        #[Assert\PositiveOrZero(groups: ['create', 'Default'])]
        #[Groups(['read:panier', 'write:panier'])]
        public ?int $total = null,

        #[Groups(['read:panier'])]
        public ?CouponResource $coupon = null,
    ) {
    }

    #[Groups(['read:panier'])]
    public function getFinalPrice(): int
    {
        if (null === $this->coupon) {
            return $this->total;
        }

        if (0 !== $this->coupon->discountValue) {
            return (int) max($this->total - $this->coupon->discountValue, 0);
        }

        if (0 !== $this->coupon->discountPercent) {
            return (int) max(ceil($this->total - ($this->total * ($this->coupon->discountPercent / 100))), 0);
        }

        return $this->total;
    }

    public static function fromModel(Panier $panier): static
    {
        $coupon = null;
        if (null !== $panier->coupon()) {
            $coupon = CouponResource::fromModel($panier->coupon());
        }

        return new self(
            Uuid::fromString($panier->id()->value),
            $panier->total()->amount,
            $coupon,
        );
    }
}
