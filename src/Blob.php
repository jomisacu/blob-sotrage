<?php

declare(strict_types=1);

namespace Jomisacu\BlobStorage;

final class Blob
{
    public readonly string $mimeType;

    public function __construct(public readonly string $key, public readonly string $binaryContents, public readonly array $metadata)
    {
        if (!isset($this->metadata['mime_type'])) {
            throw new \RuntimeException('Metadata must contain the mime_type keyword');
        }

        $this->mimeType = $this->metadata['mime_type'];
    }

    public function cloneWithNewKey(string $key): self
    {
        return new self($key, $this->binaryContents, $this->metadata);
    }
}
