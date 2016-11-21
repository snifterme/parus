<?php

namespace rokorolov\parus\page\commands;

use rokorolov\parus\page\repositories\PageRepository;
use rokorolov\parus\page\helpers\Settings;
use rokorolov\parus\admin\traits\PurifierTrait;
use rokorolov\parus\admin\exceptions\LogicException;
use Yii;

/**
 * UpdatePageHandler
 *
 * @author Roman Korolov <rokorolov@gmail.com>
 */
class UpdatePageHandler
{
    use PurifierTrait;
    
    private $pageRepository;
    
    public function __construct(PageRepository $pageRepository)
    {
        $this->pageRepository = $pageRepository;
    }
    
    public function handle(UpdatePageCommand $command)
    {
        $this->guardSlugIsUnique($command->getSlug(), $command->getId());
        
        if (null === $page = $this->pageRepository->findById($command->getId())) {
            throw new LogicException('Page does not exist.');
        }
        
        $home = false;
        $homePages = [];
        
        if ((int)$page->home !== (int)$command->getHome() && (int)$command->getHome() === Settings::homePageYesSign()) {
            $homePages = $this->pageRepository->findManyBy('home', Settings::homePageYesSign());
        }
        
        $page->status = $command->getStatus();
        $page->title = $this->textPurify($command->getTitle());
        $page->slug = $this->textPurify($command->getSlug());
        $page->content = $this->purify($command->getContent());
        $page->home = $command->getHome();
        $page->view = $this->textPurify($command->getView());
        $page->reference = $this->textPurify($command->getReference());
        $page->meta_title = $this->textPurify($command->getMetaTitle());
        $page->meta_keywords = $this->textPurify($command->getMetaKeywords());
        $page->meta_description = $this->textPurify($command->getMetaDescription());
        $page->language = $command->getLanguage();
        $page->version = $page->version + 1;
        $page->updated_at = Yii::$app->formatter->asDatetime('now', 'php:Y-m-d H:i:s');
        $page->updated_by = Yii::$app->user->identity->id;
            
        $transaction = Yii::$app->db->beginTransaction();
            
        try {
            foreach($homePages as $homePage) {
                $homePage->home = Settings::homePageNoSign();
                $this->pageRepository->update($homePage);
            }
            $this->pageRepository->update($page);
            $transaction->commit();
        } catch (\Exception $e) {
            $transaction->rollBack();
            throw $e;
        }
        
        $command->model = $page;
    }
    
    private function guardSlugIsUnique($slug, $id)
    {
        if ($this->pageRepository->existsBySlug($slug, $id)) {
            throw new LogicException('Slug already exists');
        }
    }
}