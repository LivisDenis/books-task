<?php
/**
 * @var App\Kernel\View\View $view
 * @var App\Kernel\Session\Session $session
 * @var App\Models\Book $book
 */
?>


<?php $view->component('start'); ?>
<h1 class="text-3xl">Edit book</h1>
<form class="text-[14px] mt-5" action="/books/home/update" method="post" enctype="multipart/form-data">
    <input type="hidden" name="id" value="<?= $book->id() ?>">
    <input
        class="bg-gray-100 border border-gray-300 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 p-2"
        placeholder="Type title book"
        type="text"
        value="<?= $book->title() ?>"
        name="title"
    >
    <input
        class="bg-gray-100 border border-gray-300 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 p-2"
        placeholder="Type author name"
        type="text"
        value="<?= $book->author() ?>"
        name="author"
    >
    <input type="file" name="image" value="<?= $book->image() ?>">
    <button class="bg-white border border-gray-500 hover:bg-gray-300 font-bold py-2 px-4 rounded">
        Update
    </button>
    <?php if ($session->has('title')) { ?>
        <ul style="color: red">
            <?php foreach ($session->getFlash('title') as $error) { ?>
                <li><?= $error ?></li>
            <?php } ?>
        </ul>
    <?php } ?>
</form>
<?php $view->component('end'); ?>
