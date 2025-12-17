<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Auth;
use App\Models\Blog;
use App\Models\Category;
use App\Models\User;
use App\Models\Subscription;

class BlogController extends Controller
{
    public function index(): void
    {
        $search = isset($_GET['q']) ? trim($_GET['q']) : null;
        $categoryId = isset($_GET['category']) ? (int)$_GET['category'] : null;

        $blogModel = new Blog();
        $categoryModel = new Category();

        $blogs = $blogModel->all($search, $categoryId);
        $categories = $categoryModel->all();

        $this->render('blog/index', [
            'blogs' => $blogs,
            'categories' => $categories,
            'search' => $search,
            'categoryId' => $categoryId,
        ]);
    }

    public function show(): void
    {
        $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
        $blogModel = new Blog();
        $blog = $blogModel->find($id);

        if (!$blog) {
            http_response_code(404);
            echo 'Блог не найден';
            return;
        }

        $this->render('blog/show', ['blog' => $blog]);
    }

    public function create(): void
    {
        if (!Auth::check()) {
            $this->redirect('/?controller=auth&action=login');
        }

        $categoryModel = new Category();
        $categories = $categoryModel->all();
        $error = null;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $title = trim($_POST['title'] ?? '');
            $content = trim($_POST['content'] ?? '');
            $categoryId = (int)($_POST['category_id'] ?? 0);

            if (!$title || !$content || !$categoryId) {
                $error = 'Заполните все поля';
            } else {
                $blogModel = new Blog();
                $blogModel->create(Auth::user()['id'], $categoryId, $title, $content);
                $this->redirect('/?controller=blog&action=index');
                return;
            }
        }

        $this->render('blog/create', [
            'categories' => $categories,
            'error' => $error,
        ]);
    }

    public function edit(): void
    {
        if (!Auth::check()) {
            $this->redirect('/?controller=auth&action=login');
        }

        $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
        $blogModel = new Blog();
        $blog = $blogModel->find($id);

        if (!$blog) {
            http_response_code(404);
            echo 'Блог не найден';
            return;
        }

        $currentUser = Auth::user();

        if (!Auth::isModerator() && $blog['user_id'] !== $currentUser['id']) {
            http_response_code(403);
            echo 'Недостаточно прав для редактирования';
            return;
        }

        $categoryModel = new Category();
        $categories = $categoryModel->all();
        $error = null;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $title = trim($_POST['title'] ?? '');
            $content = trim($_POST['content'] ?? '');
            $categoryId = (int)($_POST['category_id'] ?? 0);

            if (!$title || !$content || !$categoryId) {
                $error = 'Заполните все поля';
            } else {
                $blogModel->update($id, $categoryId, $title, $content);
                $this->redirect('/?controller=blog&action=show&id=' . $id);
                return;
            }
        }

        $this->render('blog/edit', [
            'blog' => $blog,
            'categories' => $categories,
            'error' => $error,
        ]);
    }

    public function delete(): void
    {
        if (!Auth::check()) {
            $this->redirect('/?controller=auth&action=login');
        }

        $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
        $blogModel = new Blog();
        $blog = $blogModel->find($id);

        if (!$blog) {
            http_response_code(404);
            echo 'Блог не найден';
            return;
        }

        $currentUser = Auth::user();

        if (!Auth::isModerator() && $blog['user_id'] !== $currentUser['id']) {
            http_response_code(403);
            echo 'Недостаточно прав для удаления';
            return;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $blogModel->delete($id);
            $this->redirect('/?controller=blog&action=index');
            return;
        }

        $this->render('blog/delete', ['blog' => $blog]);
    }

    public function author(): void
    {
        $userId = isset($_GET['user_id']) ? (int)$_GET['user_id'] : 0;
        
        if (!$userId) {
            http_response_code(404);
            echo 'Автор не найден';
            return;
        }

        $userModel = new User();
        $user = $userModel->findById($userId);

        if (!$user) {
            http_response_code(404);
            echo 'Автор не найден';
            return;
        }

        $blogModel = new Blog();
        $blogs = $blogModel->byAuthor($userId);

        $isSubscribed = false;
        if (Auth::check()) {
            $subscriptionModel = new Subscription();
            $currentUserId = Auth::user()['id'];
            $isSubscribed = $subscriptionModel->isSubscribed($currentUserId, $userId);
        }

        $this->render('blog/author', [
            'author' => $user,
            'blogs' => $blogs,
            'isSubscribed' => $isSubscribed,
        ]);
    }

    public function subscribe(): void
    {
        if (!Auth::check()) {
            $this->redirect('/?controller=auth&action=login');
            return;
        }

        $authorId = isset($_GET['user_id']) ? (int)$_GET['user_id'] : 0;
        
        if (!$authorId) {
            http_response_code(404);
            echo 'Автор не найден';
            return;
        }

        $currentUserId = Auth::user()['id'];

        // Нельзя подписаться на самого себя
        if ($currentUserId === $authorId) {
            $this->redirect('/?controller=blog&action=author&user_id=' . $authorId);
            return;
        }

        $subscriptionModel = new Subscription();
        $subscriptionModel->subscribe($currentUserId, $authorId);

        $this->redirect('/?controller=blog&action=author&user_id=' . $authorId);
    }

    public function unsubscribe(): void
    {
        if (!Auth::check()) {
            $this->redirect('/?controller=auth&action=login');
            return;
        }

        $authorId = isset($_GET['user_id']) ? (int)$_GET['user_id'] : 0;
        
        if (!$authorId) {
            http_response_code(404);
            echo 'Автор не найден';
            return;
        }

        $currentUserId = Auth::user()['id'];
        $subscriptionModel = new Subscription();
        $subscriptionModel->unsubscribe($currentUserId, $authorId);

        $this->redirect('/?controller=blog&action=author&user_id=' . $authorId);
    }

    public function subscriptions(): void
    {
        if (!Auth::check()) {
            $this->redirect('/?controller=auth&action=login');
            return;
        }

        $currentUserId = Auth::user()['id'];
        $blogModel = new Blog();
        $blogs = $blogModel->bySubscriptions($currentUserId);

        $subscriptionModel = new Subscription();
        $subscribedAuthors = $subscriptionModel->getSubscriptions($currentUserId);

        $this->render('blog/subscriptions', [
            'blogs' => $blogs,
            'subscribedAuthors' => $subscribedAuthors,
        ]);
    }
}


