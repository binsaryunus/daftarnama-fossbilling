<?php

namespace Box\Mod\Daftarnamasync\Controller;

class Admin implements \FOSSBilling\InjectionAwareInterface
{
    protected ?\Pimple\Container $di = null;

    public function setDi(\Pimple\Container $di): void
    {
        $this->di = $di;
    }

    public function getDi(): ?\Pimple\Container
    {
        return $this->di;
    }

    public function fetchNavigation(): array
    {
        return [
            'subpages' => [
                [
                    'location' => 'extensions',
                    'label' => __trans('DaftarNama Sync'),
                    'index' => 2001,
                    'uri' => $this->di['url']->adminLink('extension/settings/daftarnamasync'),
                    'class' => '',
                ],
            ],
        ];
    }

    public function register(\Box_App &$app): void
    {
        $app->get('/extension/settings/daftarnamasync', 'get_settings', [], static::class);
    }

    public function get_settings(\Box_App $app): string
    {
        $this->di['is_admin_logged'];

        return $app->render('mod_daftarnamasync_settings');
    }
}
