<?php

namespace Modules\RestAPI\Http\Controllers;

use App\Setting;
use Froiden\RestAPI\ApiResponse;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Filesystem\Filesystem;
use Symfony\Component\Console\Input\InputOption;

class LanguageController extends ApiBaseController
{
    const JSON_GROUP = '_json';

    /** @var \Illuminate\Contracts\Foundation\Application */
    protected $app;
    /** @var \Illuminate\Filesystem\Filesystem */
    protected $files;

    public function __construct(Application $app, Filesystem $files)
    {
        parent::__construct();
        $this->app = $app;
        $this->files = $files;
    }

    public function lang()
    {
        $data = $this->getTranslations();
        return ApiResponse::make(null, $data);
    }
    public function getTranslations()
    {
        $userLocale = 'en';
        if (!api_user() || api_user()->hasRole('admin')) {
            $setting = Setting::select('locale')->first();
            if ((!is_null($setting)) && (!is_null($setting->locale))) {
                $userLocale = $setting->locale;
            }
        } else {
            $userLocale = ((!is_null(api_user()->locale)) ? api_user()->locale : 'en');
        }

        $allTranslations = [];
        $base = $this->app['path.lang'];

        $langPath = $base.'/en';
        $locale = basename($langPath);
        foreach ($this->files->allfiles($langPath) as $file) {
            $info = pathinfo($file);
            $group = $info['filename'];

            $subLangPath = str_replace($langPath.DIRECTORY_SEPARATOR, '', $info['dirname']);
            $subLangPath = str_replace(DIRECTORY_SEPARATOR, '/', $subLangPath);
            $langPath = str_replace(DIRECTORY_SEPARATOR, '/', $langPath);

            if ($subLangPath != $langPath) {
                $group = $subLangPath.'/'.$group;
            }
            $translations = \Lang::getLoader()->load($locale, $group);
            $allTranslations[$group] = $translations;

            // Get another language translations
            if ($userLocale !== 'en') {
                foreach ($allTranslations[$group] as $key => $value) {
                    $mainTranslationKey = $group.'.'.$key;
                    if (is_array($value)) {
                        foreach ($allTranslations[$group][$key] as $subTransKey => $subTransValue) {
                            $translationKey = $mainTranslationKey.'.'.$subTransKey;
                            $allTranslations[$group][$key][$subTransKey] =
                                $this->getTransFromKey($translationKey, $userLocale);
                        }
                    } else {
                        $allTranslations[$group][$key] = $this->getTransFromKey($mainTranslationKey, $userLocale);
                    }
                }
            }
        }
        return $allTranslations;
    }

    public function getTransFromKey($translationKey, $userLocale)
    {
        return __(
            $translationKey,
            ['replace', 'R', InputOption::VALUE_NONE, 'Replace existing keys'],
            $userLocale
        );
    }
}
