<?php

namespace Plugins\Cms\Processors;


use Plugins\Cms\Model\Page;
use Plugins\Cms\Model\Block;

class TemplatesProcessor
{
    const BLOCK_TEMPLATE_DIRECTIVE = "'%block%'";

    protected $themesDir;
    protected $generatedViewsDir;
    protected $currentTheme;
    protected $fallbackTheme;
    protected $defaultTemplate;
    protected $content;
    protected $resultTemplateParams = [];

    protected $resultTemplate;

    /**
     * @return string
     */
    public function getDefaultTemplate()
    {
        return $this->defaultTemplate;
    }

    /**
     * @return string
     */
    public function getCurrentTheme()
    {
        if (empty($this->currentTheme)) {
            //TODO: get value from config
            $this->currentTheme = 'default';
        }

        return $this->currentTheme;
    }

    /**
     * @return string
     */
    public function getFallbackTheme()
    {
        if (empty($this->fallbackTheme)) {
            //TODO: get value from config
            $this->fallbackTheme = 'default';
        }

        return $this->fallbackTheme;
    }


    public function getThemesDir()
    {
        //TODO: get value from config
        if (empty($this->themesDir)) {
            $pluginDir = dirname(dirname(__FILE__));
            $this->themesDir = dirname(dirname($pluginDir)) . '/themes/';
        }
        return $this->themesDir;
    }

    public function processBlockTemplate(Block $block)
    {
        $templateFileRawContents = $this->getThemeTemplateFileContents($block->getRoute());
        $blockNameInTemplate = $this->addResultTemplateBlock($block);

        // Check if the required directive present in the template file
        if (!strstr($templateFileRawContents, self::BLOCK_TEMPLATE_DIRECTIVE)) {
            throw new \Exception(
                "There is no required directive {self::BLOCK_TEMPLATE_DIRECTIVE} in template {$block->getRoute()}"
            );
        }
        $templateFileRawContents = str_replace(
            self::BLOCK_TEMPLATE_DIRECTIVE,
            "\$$blockNameInTemplate",
            $templateFileRawContents
        );

        $this->content .= $templateFileRawContents;
    }

    protected function addResultTemplateBlock(Block $block)
    {
        $keyCounter = count($this->resultTemplateParams);
        $currentParamKeyName = "block_$keyCounter";
        $this->resultTemplateParams = [$currentParamKeyName => $block];

        return $currentParamKeyName;
    }

    public function applyBlocksTemplates(Block $block)
    {
        // Check if view for block has been already generated
        $resultTemplateFile = $this->getGeneratedViewsDir() . $block->getRoute();
        if (!file_exists($resultTemplateFile)) {
            // There is no view generated for this block, generate it from scratch
            $this->processBlockTemplate($block); // Process root block
            $this->iterateChildBlocks($block); // Process child blocks
            $this->generateResultTemplate($block);
        }

        // TODO: get from config
        $this->setResultTemplate('generated/' . $block->getRoute());

        return $this->getResultTemplate();
    }

    protected function iterateChildBlocks(Block $block)
    {
        $childCollection = $block->getChildren();

        if ($childCollection->getBlocks()) {
            /** @var Block $childBlock */
            foreach ($childCollection->getBlocks() as $childBlock) {
                $this->processBlockTemplate($childBlock);
                $this->iterateChildBlocks($childBlock);
            }
        }
    }

    /**
     * Returns RAW contents of a template file
     *
     * @param string $path
     * @return string
     * @throws \Exception
     */
    protected function getThemeTemplateFileContents($path)
    {
        $templatePath = $this->getThemesDir() . $this->getCurrentTheme() . '/templates/' . $path . '.blade.php';
        if (file_exists($templatePath)) { // Check if template exists in the theme's directory
            return file_get_contents($templatePath);
        } else { // If template file does not exists - search it in the default theme
            $fallbackTemplatePath = $this->getThemesDir() . $this->getFallbackTheme() .
                '/templates/' . $path . '.blade.php';
            if (file_exists($fallbackTemplatePath)) {
                return file_get_contents($fallbackTemplatePath);
            }
        }
        // There's no template in the theme and fallback theme - use default template
        $defaultTemplatePath = $this->getThemesDir() . $this->getFallbackTheme() . '/templates/default.blade.php';
        if (file_exists($defaultTemplatePath)) {
            return file_get_contents($defaultTemplatePath);
        } else {
            throw new \Exception('A default template does not exist');
        }
    }

    /**
     * @return mixed
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * @param mixed $content
     */
    public function setContent($content)
    {
        $this->content = $content;
    }

    /**
     * @return mixed
     */
    public function getGeneratedViewsDir()
    {
        if (empty($this->generatedViewsDir)) {
            //TODO: get value from config
            $pluginDir = dirname(dirname(__FILE__));
            $this->generatedViewsDir = dirname(dirname($pluginDir)) . '/resources/views/generated/';
        }

        return $this->generatedViewsDir;
    }

    public function generateResultTemplate(Block $block)
    {
        $filePath = $this->getGeneratedViewsDir() . $block->getRoute() . '.blade.php';
        $fileDirPath = dirname($filePath);
        // Create directory if it does not exist
        if (!is_dir($fileDirPath)) {
            mkdir($fileDirPath, 0755, true);
        }
        try {
            $fileHandle = fopen($filePath, 'w+');
            fwrite($fileHandle, $this->content);
            fclose($fileHandle);
            return $filePath;
        } catch (\Exception $e) {
            throw new \Exception(
                sprintf('Cannot create view file. Make sure %s dir is writable', $this->getGeneratedViewsDir())
            );
        }
    }

    /**
     * @return mixed
     */
    public function getResultTemplate()
    {
        return $this->resultTemplate;
    }

    /**
     * @param mixed $resultTemplate
     */
    public function setResultTemplate($resultTemplate)
    {
        $this->resultTemplate = $resultTemplate;
    }

    /**
     * @return array
     */
    public function getResultTemplateParams()
    {
        return $this->resultTemplateParams;
    }
}