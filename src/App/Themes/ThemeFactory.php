<?php 

namespace Kenjiefx\ScratchPHP\App\Themes;

class ThemeFactory {

    /**
     * Creates a ThemeModel instance based on the provided theme name.
     *
     * @param string $themeName The name of the theme to create.
     * @return ThemeModel The created ThemeModel instance.
     */
    public function create(string $themeName): ThemeModel {
        return new ThemeModel($themeName);
    }

}