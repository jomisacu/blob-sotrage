<?php

declare(strict_types=1);

namespace Jomisacu\BlobStorage;

final class BlobRepositoryLocal implements BlobRepositoryInterface
{
    public function __construct(private readonly string $storagePath)
    {
    }

    public function put(Blob $blob): void
    {
        file_put_contents($this->storagePath . '/blobs/' . $blob->key, $blob->binaryContents);
        file_put_contents($this->storagePath . '/blobs/' . $blob->key . '.metadata', json_encode($blob->metadata));
    }

    public function get(string $keyOnStorage): ?Blob
    {
        $filePath = $this->storagePath . '/blobs/' . $keyOnStorage;
        $metadataPath = $filePath . '.metadata';

        if (!file_exists($filePath) || !file_exists($metadataPath)) {
            return null;
        }

        $binaryContents = file_get_contents($filePath);
        $metadata = json_decode(file_get_contents($metadataPath), true);

        return new Blob($keyOnStorage, $binaryContents, $metadata);
    }

    public function delete(Blob $blob): void
    {
        $filePath = $this->storagePath . '/blobs/' . $blob->key;
        $metadataPath = $filePath . '.metadata';

        if (file_exists($filePath)) {
            unlink($filePath);
        }

        if (file_exists($metadataPath)) {
            unlink($metadataPath);
        }
    }
}
