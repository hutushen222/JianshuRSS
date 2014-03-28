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

    $note->author = trim($html->find('.people .author', 0)->plaintext);
    $note->author_uri = $html->find('.people .author', 0)->href;
    $note->book = trim($html->find('.article .article-info a', 0)->plaintext);
    $note->book_uri = $html->find('.article .article-info a', 0)->href;
    $note->author_uri = $html->find('.people .author', 0)->href;
    $note->body = $html->find('.show-content', 0)->innertext;
    $note->created = trim($html->find('.article-info p', 0)->find('text', 3));
}
