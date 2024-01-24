<?php

namespace Elfcms\Elfcms\Console\Commands;

use Elfcms\Elfcms\Aux\Locales;
use Elfcms\Elfcms\Http\Controllers\SettingController;
use Elfcms\Elfcms\Models\AccessType;
use Elfcms\Elfcms\Models\DataType;
use Elfcms\Elfcms\Models\EmailAddress;
use Elfcms\Elfcms\Models\EmailEvent;
use Elfcms\Elfcms\Models\FormFieldType;
use Elfcms\Elfcms\Models\Role;
use Elfcms\Elfcms\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;

class ElfcmsInstall extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'elfcms:install {module=all}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Install ELF CMS';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        try {
            \DB::connection()->getPDO();
            // go
        } catch (\Exception $e) {
            $this->error('No connection to the database');
            $this->line('Check connection parameters in .env file and repeat command');
            return false;
        }

        $this->line('Publishing the module ELF CMS Elfcms');
        $resultCode = Artisan::call('elfcms:publish');
        if ($resultCode == 0) {
            $this->info('OK');
        } else {
            $this->error('Publishing completed with error ' . $resultCode);
            return false;
        }
        $resultCode = false;

        $this->line('Creating storage');
        $resultCode = Artisan::call('storage:link');
        if ($resultCode == 0) {
            $this->info('OK');
        } else {
            $this->error('Storage creating completed with error ' . $resultCode);
            $this->line('You can create the storage later using the command "php artisan storage:link"');
        }
        $resultCode = false;

        $this->line('Creating database tables');
        $resultCode = Artisan::call('migrate');
        if ($resultCode == 0) {
            $this->info('OK');
        } else {
            $this->error('Table creating completed with error ' . $resultCode);
            return false;
        }
        $resultCode = false;

        $this->line('Creating basic settings');
        $settings = new SettingController;
        if ($settings->start() !== false) {
            $this->info('OK');
        } else {
            $this->error('Error');
        }

        $this->line('Creating events settings');
        $settings = new EmailEvent();
        if ($settings->start() !== false) {
            $this->info('OK');
        } else {
            $this->error('Error');
        }

        $this->line('Creating forms settings');
        $settings = new FormFieldType();
        if ($settings->start() !== false) {
            $this->info('OK');
        } else {
            $this->error('Error');
        }

        $this->line('Creating user roles settings');
        $settings = new Role();
        if ($settings->start() !== false) {
            $this->info('OK');
        } else {
            $this->error('Error');
        }

        $this->line('Creating data types settings');
        $settings = new DataType();
        if ($settings->start() !== false) {
            $this->info('OK');
        } else {
            $this->error('Error');
        }

        $this->line('Creating access types settings');
        $settings = new AccessType();
        if ($settings->start() !== false) {
            $this->info('OK');
        } else {
            $this->error('Error');
        }

        $lang = $this->choice(
            'Choose the language | Wählen Sie die Sprache | Выберите язык',
            ['English', 'Deutsch', 'Русский'],
            1,
            $maxAttempts = null,
            $allowMultipleSelections = false
        );

        $langCodes = [
            'English' => 'en',
            'Deutsch' => 'de',
            'Русский' => 'ru',
        ];
        $codesLang = [
            'en' => 'English',
            'de' => 'Deutsch',
            'ru' => 'Русский',
        ];

        config(['app.locale' => $langCodes[$lang] ?? 'en']);
        Locales::setSetting($langCodes[$lang] ?? 'en');

        $this->line('Language set:');
        $this->info($codesLang[config('app.locale')]);
        $this->newLine();

        $this->line(__('elfcms::default.create_user'));

        $roleCode = config('elfcms.elfcms.user_admin_role');
        if (empty($roleCode)) {
            $roleCode = 'admin';
        }
        $adminRole = Role::where('code', $roleCode)->first();
        if (empty($adminRole) || empty($adminRole->id)) {
            $this->error(__('elfcms::default.error_of_creating_user'));
            $this->warn('Role "Administrator" not found');
        } else {
            $countAdmin = User::whereHas('roles', function ($query) use ($adminRole) {
                $query->where('role_user.role_id', $adminRole->id);
            })->count();
            if (!empty($countAdmin)) {
                $this->warn('Admin user already exists');
            } else {
                $usermail = null;
                while (empty($usermail)) {
                    $usermail = $this->ask(__('elfcms::default.email'));
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
                }

                $checkUser = User::where('email', $usermail)->first();
                if ($checkUser) {
                    $this->error(__('elfcms::default.user_already_exists'));
                } else {
                    $userpass_confirm = null;
                    while (empty($userpass_confirm)) {
                        $userpass = $this->secret(__('elfcms::default.password'));
                        $userpass_confirm = $this->secret(__('elfcms::default.confirm_password'));
                        $validator = Validator::make(['password' => $userpass, 'password_confirmation' => $userpass_confirm], [
                            'password' => ['required', 'confirmed', Password::min(8)->letters()->numbers()],
                        ]);
                        if ($validator->fails()) {
                            $errorsData = $validator->errors()->messages();
                            $errorsMessages = [];
                            foreach ($errorsData as $message) {
                                $errorsMessages[] = $message[0];
                            }
                            $this->warn(implode('. ', $errorsMessages));
                            $userpass_confirm = null;
                        }
                    }

                    $user = User::create(['email' => $usermail, 'password' => $userpass]);

                    if (!$user) {
                        $this->error(__('elfcms::default.error_of_creating_user'));
                    } else {
                        $user->assignRole($adminRole);
                        $user->is_confirmed = 1;
                        $user->save();

                        $email = new EmailAddress();
                        $email->create([
                            'email' => $user->email,
                            'name' => 'default',
                            'active' => 1
                        ]);

                        $this->info(__('elfcms::default.user_created_successfully'));
                    }
                }
            }
        }

        $this->info('Installation completed successfully');
    }
}
