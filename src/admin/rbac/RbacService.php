<?php

namespace rokorolov\parus\admin\rbac;

use rokorolov\parus\admin\rbac\AuthorRule;
use rokorolov\parus\admin\rbac\AuthorReverseRule;
use rokorolov\parus\admin\contracts\RbacServiceInterface;
use Yii;

/**
 * RbacService.
 * 
 * @author Roman Korolov <rokorolov@gmail.com>
 */
class RbacService implements RbacServiceInterface
{
    const ROLE_SUPER_ADMIN = 'superadmin';
    const ROLE_ADMIN = 'admin';
    const ROLE_MANAGER = 'manager';
    
    /**
     * @var object|null
     */
    private $authorRule;
    
    /**
     * @var object|null
     */
    private $authorReverseRule;
    
    /**
     * Init Rbac
     */
    public function init()
    {
        $auth = Yii::$app->authManager;
        $auth->removeAll();
        
        $auth->add($this->getAuthorRule());
        $auth->add($this->getAuthorReverseRule());
        
        $accessBackend = $auth->createPermission('accessBackend');
        $accessBackend->description = 'Can access backend';
        $auth->add($accessBackend);
        
        $manager = $auth->createRole(self::ROLE_MANAGER);
        $manager->description = 'Manager';
        $auth->add($manager);
        $auth->addChild($manager, $accessBackend);
        
        $admin = $auth->createRole(self::ROLE_ADMIN);
        $admin->description = 'Admin';
        $auth->add($admin);
        $auth->addChild($admin, $manager);
        
        $superadmin = $auth->createRole(self::ROLE_SUPER_ADMIN);
        $superadmin->description = 'Super admin';
        $auth->add($superadmin);
        $auth->addChild($superadmin, $admin);
        
        $this->initUserModule($auth);
        $this->initBlogModule($auth);
        $this->initPageModule($auth);
        $this->initMenuModule($auth);
        $this->initLanguageModule($auth);
        $this->initSettingsModule($auth);
        $this->initGalleryModule($auth);
        $this->initDashboardModule($auth);
        $this->initFilemanagerModule($auth);
    }
    
    public function getOptions()
    {
        return [
            self::ROLE_ADMIN => ucfirst(self::ROLE_ADMIN),
            self::ROLE_MANAGER => ucfirst(self::ROLE_MANAGER)
        ];
    }
    
    public function getRoles()
    {
        return [
            self::ROLE_SUPER_ADMIN,
            self::ROLE_ADMIN,
            self::ROLE_MANAGER
        ];
    }
    
    public function getRoleSuperAdmin()
    {
        return self::ROLE_SUPER_ADMIN;
    }
    
    protected function initUserModule($auth)
    {
        $manageUser = $auth->createPermission('manageUser');
        $manageUser->description = 'Manage users';
        $auth->add($manageUser);
        
        $viewUser = $auth->createPermission('viewUser');
        $viewUser->description = 'View user';
        $auth->add($viewUser);
        
        $createUser = $auth->createPermission('createUser');
        $createUser->description = 'Create user';
        $auth->add($createUser);
        
        $updateUser = $auth->createPermission('updateUser');
        $updateUser->description = 'Update user';
        $auth->add($updateUser);
        
        $updateOwnUser = $auth->createPermission('updateOwnUser');
        $updateOwnUser->description = 'Update own user profile';
        $updateOwnUser->ruleName = $this->getAuthorRule()->name;
        $auth->add($updateOwnUser);
        
        $auth->addChild($updateOwnUser, $updateUser);
        
        $deleteUser = $auth->createPermission('deleteUser');
        $deleteUser->description = 'Delete user';
        $auth->add($deleteUser);
        
        $deleteOwnUser = $auth->createPermission('deleteOwnUser');
        $deleteOwnUser->description = 'Delete own user profile';
        $deleteOwnUser->ruleName = $this->getAuthorReverseRule()->name;
        $auth->add($deleteOwnUser);
        
        $auth->addChild($deleteOwnUser, $deleteUser);
        
        $admin = $auth->getRole(self::ROLE_ADMIN);
        $manager = $auth->getRole(self::ROLE_MANAGER);
        
        $auth->addChild($manager, $manageUser);
        $auth->addChild($manager, $viewUser);
        $auth->addChild($manager, $updateOwnUser);
        
        $auth->addChild($admin, $createUser);
        $auth->addChild($admin, $updateUser);
        $auth->addChild($admin, $deleteOwnUser);
    }
    
    protected function initBlogModule($auth)
    {
        $admin = $auth->getRole(self::ROLE_ADMIN);
        $manager = $auth->getRole(self::ROLE_MANAGER);
        
        $managePost = $auth->createPermission('managePost');
        $managePost->description = 'Manage posts';
        $auth->add($managePost);
        
        $viewPost = $auth->createPermission('viewPost');
        $viewPost->description = 'View post';
        $auth->add($viewPost);
        
        $createPost = $auth->createPermission('createPost');
        $createPost->description = 'Create post';
        $auth->add($createPost);
        
        $updatePost = $auth->createPermission('updatePost');
        $updatePost->description = 'Update post';
        $auth->add($updatePost);
        
        $updateOwnPost = $auth->createPermission('updateOwnPost');
        $updateOwnPost->description = 'Update own post';
        $updateOwnPost->ruleName = $this->getAuthorRule()->name;
        $auth->add($updateOwnPost);
        
        $auth->addChild($updateOwnPost, $updatePost);
        
        $deletePost = $auth->createPermission('deletePost');
        $deletePost->description = 'Delete post';
        $auth->add($deletePost);
        
        $deleteOwnPost = $auth->createPermission('deleteOwnPost');
        $deleteOwnPost->description = 'Delete own post';
        $deleteOwnPost->ruleName = $this->getAuthorRule()->name;
        $auth->add($deleteOwnPost);
        
        $auth->addChild($deleteOwnPost, $deletePost);
        
        $auth->addChild($manager, $managePost);
        $auth->addChild($manager, $viewPost);
        $auth->addChild($manager, $updatePost);
        $auth->addChild($manager, $createPost);
        
        $auth->addChild($admin, $deletePost);
        
        
        $manageCategory = $auth->createPermission('manageCategory');
        $manageCategory->description = 'Manage categories';
        $auth->add($manageCategory);
        
        $viewCategory = $auth->createPermission('viewCategory');
        $viewCategory->description = 'View category';
        $auth->add($viewCategory);
        
        $createCategory = $auth->createPermission('createCategory');
        $createCategory->description = 'Create category';
        $auth->add($createCategory);
        
        $updateCategory = $auth->createPermission('updateCategory');
        $updateCategory->description = 'Update category';
        $auth->add($updateCategory);
        
        $updateOwnCategory = $auth->createPermission('updateOwnCategory');
        $updateOwnCategory->description = 'Update own category';
        $updateOwnCategory->ruleName = $this->getAuthorRule()->name;
        $auth->add($updateOwnCategory);
        
        $auth->addChild($updateOwnCategory, $updateCategory);
        
        $deleteCategory = $auth->createPermission('deleteCategory');
        $deleteCategory->description = 'Delete category';
        $auth->add($deleteCategory);
        
        $deleteOwnCategory = $auth->createPermission('deleteOwnCategory');
        $deleteOwnCategory->description = 'Delete own category';
        $deleteOwnCategory->ruleName = $this->getAuthorRule()->name;
        $auth->add($deleteOwnCategory);
        
        $auth->addChild($deleteOwnCategory, $deleteCategory);
        
        $auth->addChild($manager, $manageCategory);
        $auth->addChild($manager, $viewCategory);
        $auth->addChild($manager, $updateCategory);
        $auth->addChild($manager, $createCategory);
        
        $auth->addChild($admin, $deleteCategory);
    }
    
    protected function initPageModule($auth)
    {
        $admin = $auth->getRole(self::ROLE_ADMIN);
        $manager = $auth->getRole(self::ROLE_MANAGER);
        
        $managePage = $auth->createPermission('managePage');
        $managePage->description = 'Manage pages';
        $auth->add($managePage);
        
        $viewPage = $auth->createPermission('viewPage');
        $viewPage->description = 'View page';
        $auth->add($viewPage);
        
        $createPage = $auth->createPermission('createPage');
        $createPage->description = 'Create page';
        $auth->add($createPage);
        
        $updatePage = $auth->createPermission('updatePage');
        $updatePage->description = 'Update page';
        $auth->add($updatePage);
        
        $updateOwnPage = $auth->createPermission('updateOwnPage');
        $updateOwnPage->description = 'Update own page';
        $updateOwnPage->ruleName = $this->getAuthorRule()->name;
        $auth->add($updateOwnPage);
        
        $auth->addChild($updateOwnPage, $updatePage);
        
        $deletePage = $auth->createPermission('deletePage');
        $deletePage->description = 'Delete page';
        $auth->add($deletePage);
        
        $deleteOwnPage = $auth->createPermission('deleteOwnPage');
        $deleteOwnPage->description = 'Delete own page';
        $deleteOwnPage->ruleName = $this->getAuthorRule()->name;
        $auth->add($deleteOwnPage);
        
        $auth->addChild($deleteOwnPage, $deletePage);
        
        $auth->addChild($manager, $managePage);
        $auth->addChild($manager, $viewPage);
        $auth->addChild($manager, $updatePage);
        $auth->addChild($manager, $createPage);
        
        $auth->addChild($admin, $deletePage);
    }
    
    protected function initMenuModule($auth)
    {
        $admin = $auth->getRole(self::ROLE_ADMIN);
        $manager = $auth->getRole(self::ROLE_MANAGER);
        
        $manageMenu = $auth->createPermission('manageMenu');
        $manageMenu->description = 'Manage menus';
        $auth->add($manageMenu);
        
        $viewMenu = $auth->createPermission('viewMenu');
        $viewMenu->description = 'View menu';
        $auth->add($viewMenu);
        
        $createMenu = $auth->createPermission('createMenu');
        $createMenu->description = 'Create menu';
        $auth->add($createMenu);
        
        $updateMenu = $auth->createPermission('updateMenu');
        $updateMenu->description = 'Update menu';
        $auth->add($updateMenu);
        
        $deleteMenu = $auth->createPermission('deleteMenu');
        $deleteMenu->description = 'Delete menu';
        $auth->add($deleteMenu);
        
        $auth->addChild($manager, $manageMenu);
        $auth->addChild($manager, $viewMenu);
        $auth->addChild($manager, $updateMenu);
        $auth->addChild($manager, $createMenu);
        
        $auth->addChild($admin, $deleteMenu);
        
        $manageMenuType = $auth->createPermission('manageMenuType');
        $manageMenuType->description = 'Manage menu types';
        $auth->add($manageMenuType);
        
        $viewMenuType = $auth->createPermission('viewMenuType');
        $viewMenuType->description = 'View menu type';
        $auth->add($viewMenuType);
        
        $createMenuType = $auth->createPermission('createMenuType');
        $createMenuType->description = 'Create menu type';
        $auth->add($createMenuType);
        
        $updateMenuType = $auth->createPermission('updateMenuType');
        $updateMenuType->description = 'Update menu type';
        $auth->add($updateMenuType);
        
        $deleteMenuType = $auth->createPermission('deleteMenuType');
        $deleteMenuType->description = 'Delete menu type';
        $auth->add($deleteMenuType);
        
        $auth->addChild($manager, $manageMenuType);
        $auth->addChild($manager, $viewMenuType);
        $auth->addChild($manager, $updateMenuType);
        $auth->addChild($manager, $createMenuType);
        
        $auth->addChild($admin, $deleteMenuType);
    }
    
    protected function initLanguageModule($auth)
    {
        $admin = $auth->getRole(self::ROLE_ADMIN);
        $manager = $auth->getRole(self::ROLE_MANAGER);
        
        $manageLanguage = $auth->createPermission('manageLanguage');
        $manageLanguage->description = 'Manage languages';
        $auth->add($manageLanguage);
        
        $viewLanguage = $auth->createPermission('viewLanguage');
        $viewLanguage->description = 'View language';
        $auth->add($viewLanguage);
        
        $createLanguage = $auth->createPermission('createLanguage');
        $createLanguage->description = 'Create language';
        $auth->add($createLanguage);
        
        $updateLanguage = $auth->createPermission('updateLanguage');
        $updateLanguage->description = 'Update language';
        $auth->add($updateLanguage);
        
        $deleteLanguage = $auth->createPermission('deleteLanguage');
        $deleteLanguage->description = 'Delete language';
        $auth->add($deleteLanguage);
        
        $auth->addChild($manager, $manageLanguage);
        $auth->addChild($manager, $viewLanguage);
        $auth->addChild($manager, $updateLanguage);
        $auth->addChild($manager, $createLanguage);
        
        $auth->addChild($admin, $deleteLanguage);
    }
    
    protected function initSettingsModule($auth)
    {
        $admin = $auth->getRole(self::ROLE_ADMIN);
        $manager = $auth->getRole(self::ROLE_MANAGER);
        
        $manageSettings = $auth->createPermission('manageSettings');
        $manageSettings->description = 'Manage settings';
        $auth->add($manageSettings);
        
        $viewSettings = $auth->createPermission('viewSettings');
        $viewSettings->description = 'View settings';
        $auth->add($viewSettings);
        
        $createSettings = $auth->createPermission('createSettings');
        $createSettings->description = 'Create settings';
        $auth->add($createSettings);
        
        $updateSettings = $auth->createPermission('updateSettings');
        $updateSettings->description = 'Update settings';
        $auth->add($updateSettings);
        
        $deleteSettings = $auth->createPermission('deleteSettings');
        $deleteSettings->description = 'Delete settings';
        $auth->add($deleteSettings);
        
        $auth->addChild($manager, $manageSettings);
        $auth->addChild($manager, $viewSettings);
        $auth->addChild($manager, $updateSettings);
        $auth->addChild($manager, $createSettings);
        
        $auth->addChild($admin, $deleteSettings);
    }
    
    protected function initGalleryModule($auth)
    {
        $admin = $auth->getRole(self::ROLE_ADMIN);
        $manager = $auth->getRole(self::ROLE_MANAGER);
        
        $manageAlbum = $auth->createPermission('manageAlbum');
        $manageAlbum->description = 'Manage album';
        $auth->add($manageAlbum);
        
        $viewAlbum = $auth->createPermission('viewAlbum');
        $viewAlbum->description = 'View album';
        $auth->add($viewAlbum);
        
        $createAlbum = $auth->createPermission('createAlbum');
        $createAlbum->description = 'Create album';
        $auth->add($createAlbum);
        
        $updateAlbum = $auth->createPermission('updateAlbum');
        $updateAlbum->description = 'Update album';
        $auth->add($updateAlbum);
        
        $deleteAlbum = $auth->createPermission('deleteAlbum');
        $deleteAlbum->description = 'Delete album';
        $auth->add($deleteAlbum);
        
        $auth->addChild($manager, $manageAlbum);
        $auth->addChild($manager, $viewAlbum);
        $auth->addChild($manager, $updateAlbum);
        $auth->addChild($manager, $createAlbum);
        
        $auth->addChild($admin, $deleteAlbum);
        
        $managePhoto = $auth->createPermission('managePhoto');
        $managePhoto->description = 'Manage photos';
        $auth->add($managePhoto);
        
        $viewPhoto = $auth->createPermission('viewPhoto');
        $viewPhoto->description = 'View photo';
        $auth->add($viewPhoto);
        
        $createPhoto = $auth->createPermission('createPhoto');
        $createPhoto->description = 'Create photo';
        $auth->add($createPhoto);
        
        $updatePhoto = $auth->createPermission('updatePhoto');
        $updatePhoto->description = 'Update photo';
        $auth->add($updatePhoto);
        
        $deletePhoto = $auth->createPermission('deletePhoto');
        $deletePhoto->description = 'Delete photo';
        $auth->add($deletePhoto);
        
        $auth->addChild($manager, $managePhoto);
        $auth->addChild($manager, $viewPhoto);
        $auth->addChild($manager, $updatePhoto);
        $auth->addChild($manager, $createPhoto);
        
        $auth->addChild($admin, $deletePhoto);
    }
    
    protected function initDashboardModule($auth)
    {
        $manager = $auth->getRole(self::ROLE_MANAGER);
        
        $manageDashboard = $auth->createPermission('manageDashboard');
        $manageDashboard->description = 'Manage dashboard';
        $auth->add($manageDashboard);
        
        $auth->addChild($manager, $manageDashboard);
    }
    
    protected function initFilemanagerModule($auth)
    {
        $manager = $auth->getRole(self::ROLE_MANAGER);
        
        $manageFilemanager = $auth->createPermission('manageFilemanager');
        $manageFilemanager->description = 'Manage dashboard';
        $auth->add($manageFilemanager);
        
        $auth->addChild($manager, $manageFilemanager);
    }
    
    protected function getAuthorRule()
    {
        if ($this->authorRule === null) {
            $this->authorRule = new AuthorRule();
        }
        return $this->authorRule;
    }
    
    protected function getAuthorReverseRule()
    {
        if ($this->authorReverseRule === null) {
            $this->authorReverseRule = new AuthorReverseRule();
        }
        return $this->authorReverseRule;
    }
}
