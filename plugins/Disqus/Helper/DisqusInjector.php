<?php

namespace Plugins\Disqus\Helper;


use App\Core\PluginInterface;
use Plugins\Blog\Resolvers\ResponseResolver;
use Plugins\Cms\Model\Page;
use App\Resolvers\ResponseResolverInterface;

class DisqusInjector
{
    /** @var  ResponseResolver */
    protected $responseResolver;
    /** @var  Page  */
    protected $page;

    public function __construct(ResponseResolverInterface $responseResolver, Page $page)
    {
        $this->responseResolver = $responseResolver;
        $this->page = $page;
    }

    /**
     * Returns current disqus template path
     *
     * @return string
     */
    public function getCommentsTemplatePath()
    {
        // TODO: get from config
        $templatesProcessor = $this->responseResolver->getTemplatesProcessor();
        $templatePath = $templatesProcessor->getThemesDir() . $templatesProcessor->getCurrentTheme() .
            '/components/plugins/disqus/comments.blade.php';
        if (!file_exists($templatePath)) {
            $templatePath = $templatesProcessor->getThemesDir() . $templatesProcessor->getFallbackTheme() .
                '/components/plugins/disqus/comments.blade.php';
        }

        return (file_exists($templatePath)? $templatePath : '' );
    }

    /**
     * Inserts disqus code into the page
     *
     * @throws \Exception
     */
    public function injectDisqusComments()
    {
        /* Add additional necessary page parameters */
        $disqusParams = [
            'pageUrl' => $this->page->getRoute(),
            'pageId' => $this->page->getRoute(),
        ];

        $existingParameters = $this->responseResolver->getRenderer()->getPageParameters();
        $this->responseResolver->getRenderer()->setPageParameters(
            array_merge($existingParameters, $disqusParams)
        );

        /* Inject comments template */
        $templateFile = $this->getCommentsTemplatePath();
        // TODO: do not write contents once again if comments code has been already injected
        if (!empty($templateFile)) {
            $templatesProcessor = $this->responseResolver->getTemplatesProcessor();
            $rawPageContents = $templatesProcessor->getContent();
            $commentsContents = file_get_contents($templateFile);
            $templatesProcessor->setContent($rawPageContents . $commentsContents);
            $templatesProcessor->generateResultViewFile($this->page->getDispatchedBlock());
        } else {
            // TODO: log error that the template does not exist
        }
    }
}