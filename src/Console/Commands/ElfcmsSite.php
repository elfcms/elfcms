<?php

namespace Elfcms\Elfcms\Console\Commands;

use Elfcms\Elfcms\Models\EmailAddress;
use Elfcms\Elfcms\Models\EmailEvent;
use Elfcms\Elfcms\Models\Form;
use Elfcms\Elfcms\Models\FormField;
use Elfcms\Elfcms\Models\FormFieldType;
use Elfcms\Elfcms\Models\Menu;
use Elfcms\Elfcms\Models\MenuItem;
use Elfcms\Elfcms\Models\Page;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Validator;

class ElfcmsSite extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'elfcms:site {--noforce}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Installing of deafult site';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->warn('The data will be overwritten!');
        $go = $this->choice(
            'Are you sure you want to install the default site?',
            ['No','Yes'],
            0,
            $maxAttempts = null,
            $allowMultipleSelections = false
        );

        if ($go == 'Yes') {

            $isIndex = $this->choice(
                'Do you want to create a home page for your website?',
                ['No','Yes'],
                0,
                $maxAttempts = null,
                $allowMultipleSelections = false
            );

            if ($isIndex == 'Yes') {
                $idexPage = Page::where('slug','start')->first();
                if (empty($idexPage)) {
                    try {
                        $idexPage = new Page();
                        $idexPage->create([
                            'slug' => 'start',
                            'name' => 'Start',
                            'path' => '/',
                            'image' => null,
                            'is_dynamic' => 0,
                            'active' => 1
                        ]);
                    }
                    catch (\Exception $e) {
                        //
                    }
                }
            }

            $email = EmailAddress::where('name','default')->first();
            if (empty($email)) {
                while (empty($usermail)) {
                    $usermail = $this->ask('Enter default email');
                    $validator = Validator::make(['email' => $usermail], [
                        'email' => 'required|email',
                    ]);
                    if ($validator->fails()) {
                        $errorsData = $validator->errors()->messages();
                        $errorsMessages = [];
                        foreach ($errorsData as $message) {
                            $errorsMessages[] = $message[0];
                        }
                        $this->warn(implode('. ', $errorsMessages));
                        $usermail = null;
                    }
                    else {
                        try {
                            $newEmail = new EmailAddress();
                            $newEmail->create([
                                'email' => $usermail,
                                'name' => 'default',
                                'active' => 1
                            ]);
                        }
                        catch (\Exception $e) {
                            //
                        }
                    }
                }
            }
            else {
                $usermail = $email->email;
            }

            $event = EmailEvent::where('code','feedback')->first() ?? null;
            if (!empty($usermail) && empty($event)) {
                try {
                    $event = new EmailEvent();
                    $event->create([
                        'code' => 'feedback',
                        'name' => 'Feedback',
                        'subject' => 'New message from website',
                        'view' => 'form-send',
                        'active' => 1
                    ]);
                }
                catch (\Exception $e) {
                    //
                }
            }

            $form = Form::where('code','feedback')->first() ?? null;
            if (!empty($event) && empty($form)) {
                try {
                    $form = new Form();
                    $form->create([
                        'code' => 'feedback',
                        'name' => 'feedback',
                        'title' => 'Feedback',
                        'event_id' => $event->id,
                        'active' => 1
                    ]);
                }
                catch (\Exception $e) {
                    //
                }
            }

            if (!empty($form)) {
                $nameField = FormField::where('form_id',$form->id)->where('name','name')->first();
                if (empty($nameField)) {
                    try {
                        $nameField = new FormField();
                        $nameField->create([
                            'title' => 'Name',
                            'name' => 'name',
                            'type_id' => FormFieldType::select('id')->where('name','text')->first()->id ?? 1,
                            'form_id' => $form->id,
                            'required' => 1,
                            'active' => 1,
                            'position' => 1
                        ]);
                    }
                    catch (\Exception $e) {
                        //
                    }
                }
                $emailField = FormField::where('form_id',$form->id)->where('name','email')->first();
                if (empty($emailField)) {
                    try {
                        $emailField = new FormField();
                        $emailField->create([
                            'title' => 'Email',
                            'name' => 'email',
                            'type_id' => FormFieldType::select('id')->where('name','email')->first()->id ?? 5,
                            'form_id' => $form->id,
                            'required' => 1,
                            'active' => 1,
                            'position' => 2
                        ]);
                    }
                    catch (\Exception $e) {
                        //
                    }
                }
                $messageField = FormField::where('form_id',$form->id)->where('name','message')->first();
                if (empty($messageField)) {
                    try {
                        $messageField = new FormField();
                        $messageField->create([
                            'title' => 'Message',
                            'name' => 'message',
                            'type_id' => FormFieldType::select('id')->where('name','textarea')->first()->id ?? 9,
                            'form_id' => $form->id,
                            'required' => 1,
                            'active' => 1,
                            'position' => 3
                        ]);
                    }
                    catch (\Exception $e) {
                        //
                    }
                }
                $submitButton = FormField::where('form_id',$form->id)->where('name','submit')->first();
                if (empty($submitButton)) {
                    try {
                        $submitButton = new FormField();
                        $submitButton->create([
                            'title' => 'OK',
                            'name' => 'ok',
                            'type_id' => FormFieldType::select('id')->where('name','submit')->first()->id ?? 15,
                            'form_id' => $form->id,
                            'active' => 1,
                            'position' => 4
                        ]);
                    }
                    catch (\Exception $e) {
                        //
                    }
                }
                $kontaktPage = Page::where('slug','kontakt')->first();
                if (empty($kontaktPage)) {
                    try {
                        $kontaktPage = new Page();
                        $kontaktPage->create([
                            'slug' => 'kontakt',
                            'name' => 'Kontakt',
                            'path' => '/kontakt',
                            'content' => '[[elfcms.form:feedback,feedback]]',
                            'is_dynamic' => 0,
                            'active' => 1
                        ]);
                    }
                    catch (\Exception $e) {
                        //
                    }
                }
            }

            $impressumPage = Page::where('slug','impressum')->first();
            if (empty($impressumPage)) {
                try {
                    $impressumPage = new Page();
                    $impressumPage->create([
                        'slug' => 'impressum',
                        'name' => 'Impressum',
                        'path' => '/impressum',
                        'image' => null,
                        'is_dynamic' => 0,
                        'active' => 1
                    ]);
                }
                catch (\Exception $e) {
                    //
                }
            }
            $menu = Menu::where('code','top')->first();
            if (empty($menu)) {
                try {
                    $menu = new Menu();
                    $menu->create([
                        'code' => 'top',
                        'name' => 'Top',
                        'active' => 1
                    ]);
                }
                catch (\Exception $e) {
                    //
                }
            }
            if (!empty($menu)) {
                $kontaktItem = MenuItem::firstOrCreate(
                    ['text'=>'Kontakt', 'menu_id'=>$menu->id],
                    ['link'=>'/kontakt','active'=>1,'clickable'=>1]
                );
                $kontaktItem->save();
                $impressumItem = MenuItem::firstOrCreate(
                    ['text'=>'Impressum', 'menu_id'=>$menu->id],
                    ['link'=>'/impressum','active'=>1,'clickable'=>1]
                );
                $impressumItem->save();
            }

            $provider = 'Elfcms\Elfcms\Providers\SitePublicProvider';
            $exitCode = Artisan::call('vendor:publish', [
                '--provider' => $provider, '--force' => !$this->option('noforce')
            ]);

            if ($exitCode == 0) {
                $this->info('Default site was installed successfully!');
            } else {
                $this->error('Installing of default site completed with error ' . $exitCode);
            }
        }
        else {
            $this->comment('Action canceled by user');
        }
    }
}
