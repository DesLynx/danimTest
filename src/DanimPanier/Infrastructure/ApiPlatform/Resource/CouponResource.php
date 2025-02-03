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
use App\DanimPanier\Domain\Event\CouponEvent;
use App\DanimPanier\Domain\Model\Coupon;
use App\DanimPanier\Infrastructure\ApiPlatform\OpenApi\CodeFilter;
use App\DanimPanier\Infrastructure\ApiPlatform\State\Processor\CreateCouponProcessor;
use App\DanimPanier\Infrastructure\ApiPlatform\State\Processor\RevokeCouponProcessor;
use App\DanimPanier\Infrastructure\ApiPlatform\State\Processor\UpdateCouponProcessor;
use App\DanimPanier\Infrastructure\ApiPlatform\State\Provider\CouponCollectionProvider;
use App\DanimPanier\Infrastructure\ApiPlatform\State\Provider\CouponEventsProvider;
use App\DanimPanier\Infrastructure\ApiPlatform\State\Provider\CouponItemProvider;
use Symfony\Component\Serializer\Attribute\Groups;
use Symfony\Component\Uid\AbstractUid;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Validator\Constraints as Assert;

#[ApiResource(
    shortName: 'Coupon',
    operations: [
        // queries
        new GetCollection(
            '/coupons/{id}/events.{_format}',
            openapiContext: ['summary' => 'Get events for this Coupon.'],
            normalizationContext: ['groups' => []],
            output: CouponEvent::class,
            provider: CouponEventsProvider::class,
        ),

        // TODO add a command to revoke the coupon, that change the state of the coupon and update the panier

        // basic crud
        new GetCollection(
            filters: [CodeFilter::class],
            provider: CouponCollectionProvider::class,
        ),
        new Get(
            provider: CouponItemProvider::class,
        ),
        new Post(
            processor: CreateCouponProcessor::class,
        ),
        new Put(
            provider: CouponItemProvider::class,
            processor: UpdateCouponProcessor::class,
            extraProperties: ['standard_put' => true],
        ),
        new Patch(
            provider: CouponItemProvider::class,
            processor: UpdateCouponProcessor::class,
        ),
        // TODO: make this a delete not a revoke
        new Delete(
            provider: CouponItemProvider::class,
            processor: RevokeCouponProcessor::class,
        ),
    ],
    normalizationContext: ['groups' => ['read:coupon']],
    denormalizationContext: ['groups' => ['write:coupon']],
)]
final class CouponResource
{
    public function __construct(
        #[ApiProperty(identifier: true, readable: false, writable: false)]
        public ?AbstractUid $id = null,

        #[Assert\NotNull]
        #[Groups(['read:coupon', 'write:coupon', 'read:panier'])]
        public ?string $code = null,

        #[Assert\NotNull]
        #[Assert\PositiveOrZero]
        #[Groups(['read:coupon', 'write:coupon', 'read:panier'])]
        public ?int $discountValue = null,

        #[Assert\NotNull]
        #[Assert\Range(min: 0, max: 100)]
        #[Groups(['read:coupon', 'write:coupon', 'read:panier'])]
        public ?int $discountPercent = null,

        #[Groups(['read:coupon'])]
        public ?string $createdAt = null,

        #[Groups(['read:coupon'])]
        public ?int $usageCount = null,
    ) {
    }

    public static function fromModel(Coupon $coupon): static
    {
        return new self(
            Uuid::fromString($coupon->id()->value),
            $coupon->code()->value,
            $coupon->discountValue()->amount,
            $coupon->discountPercent()->percentage,
            $coupon->createdAt()->value,
            $coupon->usageCount()->amount,
        );
    }
}
