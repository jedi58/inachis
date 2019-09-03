<?php

namespace App\Utils;

class ContentRevisionCompare
{
    public function doesPageMatchRevision($page, $revision): bool
    {
        return
            $revision->getContent() === $page->getContent() &&
            $revision->getTitle() === $page->getTitle() &&
            $revision->getSubTitle() === $page->getSubTitle();
    }
}
