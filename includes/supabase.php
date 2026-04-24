<?php
/**
 * Supabase PHP Wrapper for Stormbreaker Portfolio
 * Using REST API via cURL
 */

class SupabaseClient {
    private $url;
    private $key;

    public function __construct($projectRef, $apiKey) {
        $this->url = "https://{$projectRef}.supabase.co/rest/v1/";
        $this->key = $apiKey;
    }

    private function request($method, $endpoint, $data = null) {
        $ch = curl_init($this->url . $endpoint);
        $headers = [
            "apikey: {$this->key}",
            "Authorization: Bearer {$this->key}",
            "Content-Type: application/json",
            "Prefer: return=representation"
        ];

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        if ($data) {
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        }

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($httpCode >= 200 && $httpCode < 300) {
            return json_decode($response, true);
        }

        return false;
    }

    public function select($table, $order = 'id.desc') {
        return $this->request('GET', "{$table}?select=*&order={$order}");
    }

    public function insert($table, $data) {
        // Supabase REST API expects an array for bulk or single insert
        return $this->request('POST', $table, [$data]);
    }

    public function update($table, $data, $column, $value) {
        return $this->request('PATCH', "{$table}?{$column}=eq.{$value}", $data);
    }

    public function delete($table, $column, $value) {
        return $this->request('DELETE', "{$table}?{$column}=eq.{$value}");
    }

    public function querySingle($table, $column, $value) {
        $result = $this->request('GET', "{$table}?{$column}=eq.{$value}&select=*");
        return ($result && count($result) > 0) ? $result[0] : null;
    }
}
