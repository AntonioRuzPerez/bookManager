<?php
class FileCache {
    private $cacheDir;

    public function __construct($cacheDir = 'cache/') {
        $this->cacheDir = rtrim($cacheDir, '/') . '/';
        if (!is_dir($this->cacheDir)) {
            mkdir($this->cacheDir, 0777, true);
        }
    }

    public function set($key, $data, $expiration = 3600) {
        $filePath = $this->cacheDir . md5($key) . '.cache';
        $dataToStore = [
            'data' => $data,
            'expiration' => time() + $expiration
        ];
        file_put_contents($filePath, serialize($dataToStore));
    }

    public function get($key) {
        $filePath = $this->cacheDir . md5($key) . '.cache';
        if (!file_exists($filePath)) {
            return null;
        }

        $data = unserialize(file_get_contents($filePath));
        if (time() > $data['expiration']) {
            unlink($filePath);
            return null;
        }

        return $data['data'];
    }

    public function clear($key) {
        $filePath = $this->cacheDir . md5($key) . '.cache';
        if (file_exists($filePath)) {
            unlink($filePath);
        }
    }
}
