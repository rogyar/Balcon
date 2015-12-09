<?php

namespace Plugins\Cms\Processors;


use Plugins\Cms\Model\Page;
use Plugins\Cms\Model\Block;

// TODO: Refactor getting fallback parts
// TODO: Put layout file to non-public place

/**
 * Class TemplatesProcessor
 * @package Plugins\Cms\Processors
 *
 * Processes final content generation.
 */
class TemplatesProcessor
{
    /**
     * Directive inside of templates that is being replaced by
     * the actual block variable
     */
    const BLOCK_TEMPLATE_DIRECTIVE = "'%block%'";
    /**
     * Path to current themes root directory
     * @var string
     */
    protected $themesDir;
    /**
     * Path to root directory of the generated views
     * @var string
     */
    protected $generatedViewsDir;
    /**
     * Current theme name
     * @var string
     */
    protected $currentTheme;
    /**
     * Fallback theme name
     * @var string
     */
    protected $fallbackTheme;
    /**
     * Default template name
     * @var string
     */
    protected $defaultTemplate;
    /**
     * Generated raw content of the result view
     * @var string
     */
    protected $content;
    /**
     * Parameters that will be passed to the result view
     * @var array
     */
    protected $resultViewParams = [];
    /**
     * Result view name that is passed to the blade views processor
     * @var string
     */
    protected $resultView;

    public function __construct($defaultTemplate)
    {
        $this->defaultTemplate = $defaultTemplate;
        $this->generatePageLayout()
            ->applyPageLayout();
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

    /**
     * @return string
     */
    public function getThemesDir()
    {
        //TODO: get value from config
        if (empty($this->themesDir)) {
            $pluginDir = dirname(dirname(__FILE__));
            $this->themesDir = dirname(dirname($pluginDir)) . '/themes/';
        }
        return $this->themesDir;
    }

    /**
     * @return mixed
     */
    public function getResultView()
    {
        return $this->resultView;
    }

    /**
     * @param mixed $resultView
     */
    public function setResultView($resultView)
    {
        $this->resultView = $resultView;
    }

    /**
     * @return array
     */
    public function getResultViewParams()
    {
        return $this->resultViewParams;
    }

    /**
     * @return string
     */
    public function getGeneratedViewsDir()
    {
        if (empty($this->generatedViewsDir)) {
            //TODO: get value from config
            $pluginDir = dirname(dirname(__FILE__));
            $this->generatedViewsDir = dirname(dirname($pluginDir)) . '/resources/views/generated/views/';
        }

        return $this->generatedViewsDir;
    }

    /**
     * Applies templates for root block and child blocks
     *
     * @param Block $block
     * @return mixed
     * @throws \Exception
     */
    public function applyPageBlocksTemplates(Block $block)
    {
        $this->processBlockTemplate($block); // Process root block

        /* Add root block custom params to the parameters set */
        $this->resultViewParams['customParams'] = $block->getParams();

        $this->applyChildBlocksTemplates($block); // Process child blocks
        $this->generateResultViewFile($block);

        // TODO: get from config
        $this->setResultView('generated/views' . $block->getRoute());

        return $this->getResultView();
    }


    /**
     * Inserts current block into the global content
     *
     * @param Block $block
     * @throws \Exception
     */
    public function processBlockTemplate(Block $block)
    {
        $templateFileRawContents = $this->getThemeTemplateFileContents($block);
        $blockNameInTemplate = $this->addBlockToTheResultViewParamsSet($block);

        // Check if the required directive present in the template file
        if (!strstr($templateFileRawContents, self::BLOCK_TEMPLATE_DIRECTIVE)) {
            throw new \Exception(
                "There is no required directive {self::BLOCK_TEMPLATE_DIRECTIVE} in template {$block->getRoute()}"
            );
        }
        $templateFileRawContents = str_replace(
            self::BLOCK_TEMPLATE_DIRECTIVE,
            "\$page->renderParam('$blockNameInTemplate')",
            $templateFileRawContents
        );

        $this->content .= $templateFileRawContents . "\n";
    }

    /**
     * Applies templates for child blocks of the provided block
     *
     * @param Block $block
     * @throws \Exception
     */
    protected function applyChildBlocksTemplates(Block $block)
    {
        $childCollection = $block->getChildren();

        if ($childCollection->getBlocks()) {
            /** @var Block $childBlock */
            foreach ($childCollection->getBlocks() as $childBlock) {
                $this->processBlockTemplate($childBlock);
                $this->applyChildBlocksTemplates($childBlock);
            }
        }
    }

    /**
     * Adds block to the set of parameters that will be passed to the final view
     *
     * @param Block $block
     * @return string
     */
    protected function addBlockToTheResultViewParamsSet(Block $block)
    {
        $keyCounter = count($this->resultViewParams);
        $currentParamKeyName = "block_$keyCounter";
        $this->resultViewParams[$currentParamKeyName] = $block;

        return $currentParamKeyName;
    }

    /**
     * Returns RAW contents of a template file
     *
     * @param Block $block
     * @return string
     * @throws \Exception
     */
    protected function getThemeTemplateFileContents($block)
    {
        $path = $block->getTemplate() ? $block->getTemplate() : $block->getRoute();
        $templatePath = $this->getThemesDir() . $this->getCurrentTheme() . '/templates' . $path . '.blade.php';
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
        $defaultTemplatePath = $this->getThemesDir() . $this->getFallbackTheme() .
            '/templates/' . $this->getDefaultTemplate();
        if (file_exists($defaultTemplatePath)) {
            return file_get_contents($defaultTemplatePath);
        } else {
            throw new \Exception('A default template does not exist');
        }
    }

    /**
     * Generates file for the result view
     *
     * @param Block $block
     * @return string
     * @throws \Exception
     */
    protected function generateResultViewFile(Block $block)
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
     * Creates actual layout file in generated content
     */
    protected function generatePageLayout()
    {
        $generatedLayoutFile = dirname($this->getGeneratedViewsDir()) . '/layout.blade.php';

        /* Generate layout if it has not been generated yet */
        if (!file_exists($generatedLayoutFile)) {
            $themeLayoutFile = $this->getThemesDir() . $this->getCurrentTheme() . '/components/layout.blade.php';
            /* Generate layout from the fallback theme */
            if (!file_exists($themeLayoutFile)) {
                $themeLayoutFile = $this->getThemesDir() . $this->getFallbackTheme() . '/components/layout.blade.php';
            }
            if (!file_exists($themeLayoutFile)) {
                throw new \Exception("No layout file has been found");
            }
            try {
                copy($themeLayoutFile, $generatedLayoutFile);
            } catch (\Exception $e) {
                throw new \Exception(
                    sprintf('Cannot generate layout file. Make sure %s dir is writable', $this->getGeneratedViewsDir())
                );
            }
        }

        return $this;
    }

    /**
     * Adds layout extension to the result view
     */
    protected function applyPageLayout()
    {
        $this->content = "
        @extends('generated/layout')
        ";
    }
}