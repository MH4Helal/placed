<?php

namespace modules\PlacesModule;

use Craft;
use craft\base\Module as BaseModule;
use yii\base\Event;
use craft\web\UrlManager;
use craft\events\RegisterUrlRulesEvent;
use Psr\Log\LoggerInterface;

class PlacesModule extends BaseModule
{
    public function init()
    {
        parent::init();
    
        // Register PlacesService as a component
        $this->setComponents([
            'places' => \modules\PlacesModule\services\PlacesService::class,
        ]);
    
        // Register PlaceController URL rules
        Event::on(
            UrlManager::class,
            UrlManager::EVENT_REGISTER_CP_URL_RULES,
            function (RegisterUrlRulesEvent $event) {
                $event->rules['places'] = 'places/place/index';
                $event->rules['places/get-place-info'] = 'places/place/get-place-info-by-id';
                $event->rules['places/search'] = 'places/place/search-places';
            }
        );
    
        // Perform database migrations
        $this->performMigrations();
    }

    private function performMigrations()
    {
        // Check if the migration has already been applied
        $migrationName = 'm240322_000000_create_places_table';
        $appliedMigrations = Craft::$app->getDb()->getMigrationHistory(null);
        if (!in_array($migrationName, $appliedMigrations)) {
            // Apply the migration
            $migration = new \modules\PlacesModule\migrations\m200101_000000_create_places_table();
            ob_start();
            $migration->up();
            ob_end_clean();
            // Log the migration as applied
            Craft::$app->getDb()->createCommand()->insert('{{%migration}}', [
                'version' => $migrationName,
                'apply_time' => time(),
            ])->execute();
        }
    }
}

