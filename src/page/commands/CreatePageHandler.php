<?php

namespace rokorolov\parus\page\commands;

use rokorolov\parus\page\repositories\PageRepository;
use rokorolov\parus\page\helpers\Settings;
use rokorolov\parus\admin\traits\PurifierTrait;
use rokorolov\parus\admin\exceptions\LogicException;
use Yii;

/**
 * CreatePageHandler
 *
 * @author Roman Korolov <rokorolov@gmail.com>
 */
class CreatePageHandler
{
    use PurifierTrait;
    
    private $pageRepository;
    
    public function __construct(PageRepository $pageRepository)
    {
        $this->pageRepository = $pageRepository;
    }
    
    public function handle(CreatePageCommand $command)
    {
        $this->guardSlugIsUnique($command->getSlug());
        
        $homePages = [];
        
        if ((int)$command->getHome() === Settings::homePageYesSign()) {
            $homePages = $this->pageRepository->findManyBy('home', Settings::homePageYesSign());
        }
        
        $datetime = Yii::$app->formatter->asDatetime('now', 'php:Y-m-d H:i:s');
        $userId = Yii::$app->user->identity->id;
        
        $page = $this->pageRepository->makePageCreateModel();
        $page->status = $command->getStatus();
        $page->language = $command->getLanguage();
        $page->title = $this->textPurify($command->getTitle());
        $page->slug = $this->textPurify($command->getSlug());
        $page->content = $this->purify($command->getContent());
        $page->home = $command->getHome();
        $page->view = $this->textPurify($command->getView());
        $page->reference = $this->textPurify($command->getReference());
        $page->meta_title = $this->textPurify($command->getMetaTitle());
        $page->meta_keywords = $this->textPurify($command->getMetaKeywords());
        $page->meta_description = $this->textPurify($command->getMetaDescription());
        $page->version = 1;
        $page->created_at = $datetime;
        $page->updated_at = $datetime;
        $page->created_by = $userId;
        $page->updated_by = $userId;
        
        $transaction = Yii::$app->db->beginTransaction();
        
        try {
            foreach($homePages as $homePage) {
                $homePage->home = Settings::homePageNoSign();
                $this->pageRepository->update($homePage);
            }
            $this->pageRepository->add($page);
            $transaction->commit();
        } catch (\Exception $e) {
            $transaction->rollBack();
            throw $e;
        }
        
        $command->model = $page;
    }

    private function guardSlugIsUnique($slug)
    {
        if ($this->pageRepository->existsBySlug($slug)) {
            throw new LogicException('Slug already exists');
        }
    }
}