<?php
/* @var $url
 * @var $id
 * @var $title
 * @var $desc
 * @var $text
 * @var $text2
 * @var $info
 */

if ($info) {
    echo "<div class=' {$info['status']}'> {$info['text']}</div>";
} ?>
<form method='POST'>
    <label>
        <input name='id' value="<?= $id ?>" hidden='hidden'>
    </label><br>
    <label for='url'> url:<br>
        <input name='url' placeholder='url' value="<?= $url ?>">
    </label><br>
    <label for='title'>title:<br>
        <input name='title' placeholder='title' value="<?= $title ?>">
    </label><br>
    <label for='description'>description:<br>
        <input name='description' placeholder='description' value="<?= $desc ?>">
    </label><br>
    <label>text<br>
        <textarea name='text' placeholder='text'><?= $text ?></textarea>
    </label><br>
    <label>text2<br>
        <textarea name='text2' placeholder='text2'><?= $text2 ?></textarea>
    </label><br>
    <input type='submit' value='Save'><br><br>
</form>

