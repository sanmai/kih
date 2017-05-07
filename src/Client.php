<?php

declare(strict_types=1);

namespace KiH;

use GuzzleHttp\Client as HttpClient;
use Psr\Http\Message\StreamInterface;

final class Client
{
    private const SHARE_API = 'https://onedrive.live.com/redir.aspx';

    private const REST_API = 'https://api.onedrive.com/v1.0';

    /** @var HttpClient */
    private $client;

    /** @var array */
    private $share;

    public function __construct(HttpClient $client, array $share)
    {
        $this->client = $client;
        $this->share = $share;
    }

    public function getFolder() : array
    {
        $data = $this->decode(
            (string) $this->request(['root', 'children'], [
                'select' => implode(',', [
                    'audio',
                    'createdDateTime',
                    'file',
                    'id',
                    'size',
                    'webUrl',
                ]),
                'orderby' => 'lastModifiedDateTime desc',
                'top' => 10,
            ])
        );

        if (!isset($data['value'])) {
            throw new Exception('The folder representation does not contain the "value" element');
        }

        return array_map(function (array $file) {
            $file['createdDateTime'] = new \DateTime($file['createdDateTime']);

            return $file;
        }, $data['value']);
    }

    public function getItem(string $id) : array
    {
        return $this->decode(
            (string) $this->request(['items', $id])
        );
    }

    private function request(array $path, array $query = []) : StreamInterface
    {
        $url = self::REST_API . '/' . implode('/', array_map('rawurlencode', array_merge([
            'shares',
            'u!' . base64_encode(
                self::SHARE_API . '?' . http_build_query($this->share)
            ),
        ], $path)));

        if ($query) {
            $url .= '?' . http_build_query($query);
        }

        return $this->client
            ->request('GET', $url)
            ->getBody();
    }

    private function decode(string $json) : array
    {
        $data = json_decode($json, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new Exception('Cannot decode API response: ' . json_last_error_msg());
        }

        return $data;
    }
}
