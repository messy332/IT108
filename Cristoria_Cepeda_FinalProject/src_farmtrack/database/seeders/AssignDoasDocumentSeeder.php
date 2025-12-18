<?php

namespace Database\Seeders;

use App\Models\Farmer;
use Illuminate\Database\Seeder;

class AssignDoasDocumentSeeder extends Seeder
{
    public function run()
    {
        $doasPath = 'supporting_documents/1764953082_DOAS.pdf';

        Farmer::whereNull('supporting_document')
            ->orWhere('supporting_document', '')
            ->update(['supporting_document' => $doasPath]);
    }
}
