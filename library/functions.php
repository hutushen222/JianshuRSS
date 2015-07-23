<?php

function str_start_with($needle, $haystack) {
    return !strncmp($haystack, $needle, strlen($needle));
}

function str_end_with($needle, $haystack) {
    $length = strlen($needle);
    if ($length == 0) { return true; }

    return (substr($haystack, -$length) === $needle);
}

/**
 * 抓取简书文章
 *
 * @param stdClass $note
 */
function fetchNote($note) {
    $note_file_path = NOTES_ROOT . DS . md5($note->uri) . '.html';
    if (file_exists($note_file_path)) {
        $html_str = file_get_contents($note_file_path);
    } else {
        $html_str = file_get_contents(JIANSHU . $note->uri);
        if ($html_str === false) {
            throw new Exception('Can not fetch Jianshu note\'s content with uri: ' . $note->uri);
        }
        file_put_contents($note_file_path, $html_str);
    }

    $html = str_get_html($html_str);

    $author = $html->find('a.author-name', 0);
    $note->author = trim($author->plaintext);
    $note->author_uri = $author->href;

    if (!isset($note->book)) {
        $note->book = '';
        $note->book_uri = '';
    }

    $note->body = $html->find('.article .show-content', 0)->innertext;
    $created = $html->find('.article .meta-top span', 1)->plaintext;

    $note->created = strtr($created, '.', '-');
}
