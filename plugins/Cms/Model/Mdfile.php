<?php

namespace Plugins\Cms\Model;

use \Symfony\Component\Yaml\Yaml;

/**
 * Class Mdfile
 * @package Plugins\Cms\Model
 *
 * Represents a raw .md file with routines to parse it's contents
 */
class Mdfile
{
    /**
     * Absolute path to the block's md file
     * @var  string
     */
    protected $path;
    /**
     * A set of parameters that will be passed to the template
     * Available params:
     * - title - page title tag
     * - description - meta-description tag
     * - name - page name in navigation menu
     * - exclude_from_navigation - if true, the page is not displayed in navigation menu
     *
     * @var array
     */
    protected $params;
    /**
     * File contents (exclude headers)
     * @var  string
     */
    protected $content;

    /**
     *  File attributes: updated_at, filename, owner
     * @var array
     */
    protected $fileAttrs;

    /**
     * @return string
     * @throws \Exception
     */
    public function getContent()
    {
        if (!$this->content) {
            $blockMdFile = $this->getPath() . '/' . $this->getFileAttrs()['filename'];
            if (file_exists($blockMdFile)) {
                $rawContent = file_get_contents($blockMdFile);
                $this->markdownHeaderParse($rawContent);
            } else {
                throw new \Exception("The block in {$this->getPath()} does not have .md file inside");
            }
        }
        return $this->content;
    }

    /**
     * @param string $content
     */
    public function setContent($content)
    {
        $this->content = $content;
    }

    /**
     * @return array
     */
    public function getParams()
    {
        if (!$this->params) {
            $this->getContent();
        }

        return $this->params;
    }

    /**
     * @param array $params
     */
    public function setParams($params)
    {
        $this->params = $params;
    }

    /**
     * @param string $path
     */
    public function setPath($path)
    {
        $this->path = $path;
    }

    /**
     * @return string
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * @return array
     */
    public function getFileAttrs()
    {
        return $this->fileAttrs;
    }

    /**
     * @param array $fileAttrs
     */
    public function setFileAttrs($fileAttrs)
    {
        $this->fileAttrs = $fileAttrs;
    }

    /**
     * Parses the file by extracting it's header params
     * and raw content
     *
     * @param $contents
     * @return $this
     */
    protected function markdownHeaderParse($contents)
    {
        $frontmatter_regex = "/^---\n(.+?)\n---\n{0,}(.*)$/uis";

        /* Make line endings compatible with Unix format */
        $contents = preg_replace("/(\r\n|\r)/", "\n", $contents);

        /* Parse header */
        preg_match($frontmatter_regex, ltrim($contents), $frontMatterRaw);
        if(!empty($frontMatterRaw)) {
            $frontmatter = preg_replace("/\n\t/", "\n    ", $frontMatterRaw[1]);
            $this->setParams((array) Yaml::parse($frontmatter));
            $this->setContent($frontMatterRaw[2]);
        } else {
            $this->setParams([]);
            $this->setContent($contents);
        }

        return $this;
    }
}