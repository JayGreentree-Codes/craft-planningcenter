<?php
namespace jaygreentreecodes\planningcenter;

use Craft;
use craft\base\Plugin as BasePlugin;
use jaygreentreecodes\planningcenter\models\Settings;
use jaygreentreecodes\planningcenter\services\PlanningCenterService;

/**
 * Planning Center plugin class.
 * @property PlanningCenterService $api
 */
class Plugin extends BasePlugin
{
    public static ?Plugin $plugin;
    public bool $hasCpSettings = true;

    public function init(): void
    {
        parent::init();
        self::$plugin = $this;

        // Register components
        $this->setComponents([
            'api' => PlanningCenterService::class,
        ]);

        Craft::info('Planning Center plugin loaded', __METHOD__);
    }

    protected function createSettingsModel(): ?craft\base\Model
    {
        return new Settings();
    }

    protected function settingsHtml(): ?string
    {
        return Craft::$app->getView()->renderTemplate('planning-center/settings', [
            'settings' => $this->getSettings()
        ]);
    }
}
