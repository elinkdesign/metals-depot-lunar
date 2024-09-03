<?php

namespace Database\Seeders;

use Illuminate\Support\Facades\DB;
use Lunar\FieldTypes\Text;
use Lunar\FieldTypes\TranslatedText;
use Lunar\Models\Collection;
use Lunar\Models\CollectionGroup;
use Illuminate\Support\Facades\Log;

class CollectionSeeder extends AbstractSeeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $collections = $this->getSeedData('collections');

        DB::transaction(function () use ($collections) {
            // Ensure we have a CollectionGroup
            $collectionGroup = CollectionGroup::firstOrCreate(
                ['handle' => 'default'],
                ['name' => 'Default Group']
            );

            Log::info('CollectionGroup:', ['id' => $collectionGroup->id, 'name' => $collectionGroup->name]);

            foreach ($collections as $collection) {
                Log::info('Creating collection:', ['name' => $collection->name]);

                try {
                    Collection::create([
                        'collection_group_id' => $collectionGroup->id,
                        'attribute_data' => [
                            'name' => new TranslatedText([
                                'en' => new Text($collection->name),
                            ]),
                            'description' => new TranslatedText([
                                'en' => new Text($collection->description ?? ''),
                            ]),
                        ],
                    ]);
                } catch (\Exception $e) {
                    Log::error('Failed to create collection:', [
                        'name' => $collection->name,
                        'error' => $e->getMessage()
                    ]);
                }
            }
        });
    }
}