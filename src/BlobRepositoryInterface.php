<?php

declare(strict_types=1);

namespace Jomisacu\BlobStorage;

interface BlobRepositoryInterface
{
    public function put(Blob $blob): void;

    public function get(string $keyOnStorage): ?Blob;

    public function delete(Blob $blob): void;
}
