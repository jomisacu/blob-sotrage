<?php

declare(strict_types=1);

namespace Jomisacu\BlobStorage;

use Aws\Result;
use Aws\S3\S3ClientInterface;
use Exception;
use Psr\Http\Message\StreamInterface;

final class BlobRepositoryS3 implements BlobRepositoryInterface
{
    public function __construct(private readonly S3ClientInterface $s3Client, private readonly string $bucket)
    {
    }

    public function put(Blob $blob): void
    {
        $this->s3Client->putObject([
            'Bucket' => $this->bucket,
            'Key' => $blob->key,
            'Body' => $blob->binaryContents,
            'Metadata' => $blob->metadata,
        ]);
    }

    public function get(string $keyOnStorage): ?Blob
    {
        /**
         * @var Result $result
         * @var string|null|StreamInterface $body
         */

        try {
            $args = [
                'Bucket' => $this->bucket,
                'Key' => $keyOnStorage,
            ];

            $result = $this->s3Client->getObject($args);
            $body = $result->get('Body');
            $metadata = $result->get('Metadata');

            $blob = new Blob($keyOnStorage, $body instanceof StreamInterface ? $body->getContents() : $body, $metadata);
        } catch (Exception) {
            $blob = null;
        }

        return $blob;
    }

    public function delete(Blob $blob): void
    {
        $this->s3Client->deleteObject([
            'Bucket' => $this->bucket,
            'Key' => $blob->key,
        ]);
    }
}
