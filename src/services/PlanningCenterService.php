<?php
namespace modules\planningcenter\services;

use Craft;
use craft\base\Component;
use modules\planningcenter\Plugin;
use GuzzleHttp\Exception\GuzzleException;

class PlanningCenterService extends Component
{
    /**
     * Fetch endpoints from the Planning Center API
     * Example: Plugin::$plugin->api->get('services/v2/service_types')
     */
    public function get(string $endpoint, array $query = []): array
    {
        $settings = Plugin::$plugin->getSettings();
        $appId = Craft::parseEnv($settings->appId);
        $secret = Craft::parseEnv($settings->secret);

        if (!$appId || !$secret) {
            Craft::error('Planning Center API keys are missing in settings.', __METHOD__);
            return [];
        }

        $client = Craft::createGuzzleClient([
            'base_uri' => 'https://api.planningcenteronline.com/',
            'auth' => [$appId, $secret]
        ]);

        try {
            $response = $client->request('GET', $endpoint, ['query' => $query]);
            return json_decode($response->getBody()->getContents(), true) ?? [];
        } catch (GuzzleException $e) {
            Craft::error('Planning Center API error: ' . $e->getMessage(), __METHOD__);
            return [];
        }
    }
}
