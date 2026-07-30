<?php

namespace App\Services\Serializer;

use App\Entity\Actor;
use App\Entity\File\MediaObject;
use App\Entity\HighlightedItem;
use App\Entity\Project;
use App\Entity\Resource;
use App\Enum\Config\ImagineFilter;
use Liip\ImagineBundle\Imagine\Cache\CacheManager;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Vich\UploaderBundle\Storage\StorageInterface;

class MediaObjectNormalizer implements NormalizerInterface
{
    private const ALREADY_CALLED = 'MEDIA_OBJECT_NORMALIZER_ALREADY_CALLED';

    public function __construct(
        #[Autowire(service: 'api_platform.jsonld.normalizer.item')]
        private readonly NormalizerInterface $normalizer,
        private readonly StorageInterface $storage,
        private readonly CacheManager $imagineCacheManager,
    ) {
    }

    public function normalize($object, ?string $format = null, array $context = []): array|string|int|float|bool|\ArrayObject|null
    {
        $context[self::ALREADY_CALLED] = true;
        /* @var MediaObject $object */
        if ($this->isActor($context) || $this->isProject($context) || $this->isResource($context) || $this->isHighlightedItem($context)) {
            $object->contentsFilteredUrl = [
                ImagineFilter::THUMBNAIL => $this->imagineCacheManager->getBrowserPath(
                    $this->storage->resolveUri($object, 'file'),
                    ImagineFilter::THUMBNAIL
                ),
            ];
        }

        $object->contentUrl = $this->storage->resolveUri($object, 'file');

        return $this->normalizer->normalize($object, $format, $context);
    }

    public function supportsNormalization($data, ?string $format = null, array $context = []): bool
    {
        if (isset($context[self::ALREADY_CALLED])) {
            return false;
        }

        return $data instanceof MediaObject && (
            $this->isActor($context)
            || $this->isProject($context)
            || $this->isResource($context)
            || $this->isHighlightedItem($context)
        );
    }

    private function isActor(array $context = []): bool
    {
        return $this->hasObjectcontext($context) && $context['object'] instanceof Actor;
    }

    private function isProject(array $context = []): bool
    {
        return $this->hasObjectcontext($context) && $context['object'] instanceof Project;
    }

    private function isResource(array $context = []): bool
    {
        return $this->hasObjectcontext($context) && $context['object'] instanceof Resource;
    }

    private function isHighlightedItem(array $context = []): bool
    {
        return $this->hasObjectcontext($context) && $context['object'] instanceof HighlightedItem;
    }

    private function hasObjectContext(array $context = []): bool
    {
        return isset($context['object']);
    }

    public function getSupportedTypes(?string $format): array
    {
        return [
            MediaObject::class => true,
        ];
    }
}
