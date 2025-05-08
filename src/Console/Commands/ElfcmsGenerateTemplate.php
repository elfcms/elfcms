<?php

namespace Elfcms\Elfcms\Console\Commands;

use Illuminate\Console\Command;
use Elfcms\Elfcms\Models\Page;
use Elfcms\Elfcms\Models\Menu;
use Elfcms\Elfcms\Models\MenuItem;
use Elfcms\Forms\Models\Form;
use Elfcms\Infobox\Models\Infobox;
use Illuminate\Support\Str;

class ElfcmsGenerateTemplate extends Command
{
    protected $signature = 'elfcms:generate-template {template?} {--site=Website}';

    protected $description = 'Generate a site structure based on a predefined template';

    public function handle()
    {
        $template = $this->argument('template');

        if (!$template) {
            $template = $this->choice('Choose a template', [
                'classic_business_card' => 'Classic Business Card',
                'single_page_portfolio' => 'Single Page Portfolio',
                'personal_blog' => 'Personal Blog',
                'company_website' => 'Company Website',
                'product_catalog' => 'Product Catalog'
            ], 'classic_business_card');
        }

        $siteName = $this->option('site') ?? 'Website';

        $this->info("Generating site structure for template: $template");

        match ($template) {
            'classic_business_card' => $this->generateClassicBusinessCard($siteName),
            default => $this->error("Template '$template' not found."),
        };
    }

    protected function generateClassicBusinessCard(string $siteName)
    {
        $pages = [
            ['slug' => '/', 'title' => 'Startseite', 'content' => "<h1>$siteName</h1><p>Willkommen!</p>"],
            ['slug' => '/about', 'title' => 'Über uns', 'content' => '<h2>Über uns</h2><p>Kurze Geschichte...</p>'],
            ['slug' => '/services', 'title' => 'Leistungen', 'content' => '<h2>Unsere Leistungen</h2><ul><li>Beratung</li><li>Entwicklung</li></ul>'],
            ['slug' => '/contact', 'title' => 'Kontakt', 'content' => '<h2>Kontaktformular</h2><div>{form:contact}</div>'],
            ['slug' => '/impressum', 'title' => 'Impressum', 'content' => '<h2>Impressum</h2><p>Angaben gemäß § 5 TMG</p>'],
        ];

        $this->info("Creating pages...");
        foreach ($pages as $pageData) {
            $page = Page::create([
                'title' => $pageData['title'],
                'slug' => $pageData['slug'],
                'content' => $pageData['content'],
                'active' => true,
            ]);
            $this->info(" - Created page: {$page->title}");
        }

        $this->info("Creating menu...");
        $menu = Menu::create([
            'name' => 'main',
            'title' => 'Hauptmenü',
            'active' => true,
        ]);

        foreach ($pages as $order => $pageData) {
            MenuItem::create([
                'menu_id' => $menu->id,
                'title' => $pageData['title'],
                'url' => $pageData['slug'],
                'order' => $order,
                'active' => true,
            ]);
        }

        $this->info("Creating contact form...");
        $form = Form::create([
            'name' => 'contact',
            'title' => 'Kontaktformular',
            'action' => '/contact/send',
            'method' => 'POST',
            'success_message' => 'Nachricht erfolgreich gesendet.',
            'fail_message' => 'Fehler beim Senden der Nachricht.',
        ]);

        $form->fields()->createMany([
            ['type' => 'text', 'name' => 'name', 'placeholder' => 'Ihr Name'],
            ['type' => 'email', 'name' => 'email', 'placeholder' => 'Ihre E-Mail'],
            ['type' => 'textarea', 'name' => 'message', 'placeholder' => 'Nachricht'],
        ]);

        $this->info("Classic business card site structure generated successfully.");
    }
}
