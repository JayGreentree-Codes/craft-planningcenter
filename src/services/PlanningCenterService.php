<?php
namespace jaygreentreecodes\planningcenter\services;

use Craft;
use craft\base\Component;
use jaygreentreecodes\planningcenter\Plugin;
use GuzzleHttp\Exception\GuzzleException;

class PlanningCenterService extends Component
{
    /**
     * Fetch upcoming events from Planning Center Calendar
     * Example: Plugin::$plugin->api->getCalendarEvents(['per_page' => 10])
     */
    public function getCalendarEvents(array $query = []): array
    {
        return $this->get('calendar/v2/events', $query);
    }

    /**
     * Fetch people profiles from Planning Center People
     * Example: Plugin::$plugin->api->getPeople(['per_page' => 10])
     */
    public function getPeople(array $query = []): array
    {
        return $this->get('people/v2/people', $query);
    }

    /**
     * Fetch upcoming plans for a specific service type
     * Example: Plugin::$plugin->api->getServicePlans('123456', ['per_page' => 5])
     */
    public function getServicePlans(string $serviceTypeId, array $query = []): array
    {
        return $this->get("services/v2/service_types/{$serviceTypeId}/plans", $query);
    }

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
