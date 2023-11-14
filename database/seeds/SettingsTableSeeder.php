<?php

use Illuminate\Database\Seeder;

class SettingsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $settings = [
            'site_name'           => ['Unilever Transport Inbound Portal', 'Website title'],
            'site_owner'          => ['Supply Chain', 'Enter client, owner or company full name'],
            'base_url'            => [env('APP_DOMAIN', 'myutip.com'), 'Enter domain name here'],
            'http_url'            => [env('APP_HTTP_URL', 'http://myutip.com'), 'Enter http url'],
            'https_url'           => [env('APP_HTTPS_URL', 'https://myutip.com'), 'Enter https url here if exist'],
            'version'             => ['1.0.0', 'Application version detail'],
            'email.owner'         => ['zarpio@gmail.com', 'Enter client email address'],
            'email.info'          => ['admin@example.com', 'Enter site info email address'],
            'email.support'       => ['admin@example.com', 'Enter site support email address'],
            'email.no_reply'      => ['no-reply@example.com', 'Enter site no-reply email address, all emails will be sent by this email'],
            'theme.frontend'      => ['default', 'Enter the name of frontend theme'],
            'theme.backend.admin' => ['metronic', 'Enter the name of backend theme'],
            'date.format'         => ['M d, Y', 'Enter the date format'],
            'datetime.format'     => ['M d, Y h:ia', 'Enter the datetime format'],
            'phone'               => ['+92-321-2441860', 'Website phone number'],
            'address'             => ['Unilever Head Office, Karachi - Pakistan.', 'Website Address'],
            'country'             => ['Pakistan.', 'Website Country Name'],
            'social.linked'       => ['https://pk.linkedin.com/', 'Social links'],
            'social.twitter'      => ['https://twitter.com/', 'Social links'],
            'social.facebook'     => ['https://www.facebook.com/', 'Social links'],
            'social.instagram'    => ['https://www.instagram.com/', 'Social links'],
            'maintenance.mode'    => [false, 'If you are going to update anything, set it true'],
            'show.notifications'  => [false, 'Notifications dropdown in header'],
            'show.inbox'          => [false, 'Inbox dropdown in header'],
            'show.todo'           => [false, 'TODO dropdown in header'],
            'show.user_links'     => [true, 'User Links dropdown in header'],
            'show.quick_sidebar'  => [false, 'Quick Sidebar enable/disable'],
        ];

        foreach ($settings as $key => $value) {
            DB::table('settings')->insert(
                [
                    'key'         => $key,
                    'value'       => $value[0],
                    'description' => $value[1],
                    'created_at'  => date('Y-m-d H:i:s'),
                    'updated_at'  => date('Y-m-d H:i:s'),
                ]
            );
        }
    }
}
