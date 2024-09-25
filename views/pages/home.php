<?php
/**
 * @var App\Kernel\View\View $view
 * @var App\Kernel\Session\Session $session
 * @var array<App\Models\Book> $books
 */
?>


<?php $view->component('start'); ?>
<h1 class="text-3xl">Add books</h1>
<form class="text-[14px] mt-5" action="/books/home" method="post" enctype="multipart/form-data">
    <input
        class="bg-gray-100 border border-gray-300 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 p-2"
        placeholder="Type title book"
        type="text"
        name="title"
    >
    <input
        class="bg-gray-100 border border-gray-300 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 p-2"
        placeholder="Type author name"
        type="text"
        name="author"
    >
    <input type="file" name="image">
    <button class="bg-white border border-gray-500 hover:bg-gray-300 font-bold py-2 px-4 rounded">
        Add
    </button>
    <?php if ($session->has('title')) { ?>
        <ul style="color: red">
            <?php foreach ($session->getFlash('title') as $error) { ?>
                <li><?= $error ?></li>
            <?php } ?>
        </ul>
    <?php } ?>
</form>
    <div class="flex flex-col flex-auto gap-5 mt-10">
        <?php foreach ($books as $book) { ?>
            <div class="flex items-center gap-3 w-full bg-gray-200 rounded-[10px] p-2">
                <img class="rounded w-[50px] h-[50px] object-cover" src="<?= $book->image() ?>" alt="<?= $book->title() ?>">
                <div class="flex gap-3 items-center">
                    <h2 class="text-[16px] font-bold">Title: <?= $book->title() ?></h2>
                    <span>Author: <?= $book->author() ?></span>
                </div>
                <div class="ml-auto flex gap-3 items-center">
                    <a href="/books/home/update?id=<?= $book->id() ?>" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                        Edit
                    </a>
                    <form action="/books/home/destroy" method="post">
                        <input type="hidden" name="id" value="<?= $book->id() ?>">
                        <button class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                            Delete
                        </button>
                    </form>
                </div>
            </div>
        <?php } ?>
    </div>
<?php $view->component('end'); ?>
