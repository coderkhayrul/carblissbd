<?php

namespace App\Services;

use App\Models\WebSocialLink;

class SocialLinkService
{
    public function update(WebSocialLink $socialLink, array $data): void
    {
        $socialLink->fill($data);
        $socialLink->saveOrFail();
    }

    public function delete(WebSocialLink $socialLink): void
    {
        if (!$socialLink->delete()) {
            throw new \RuntimeException('Unable to delete the social link.');
        }
    }

    public function updateStatus(WebSocialLink $socialLink, bool $status): void
    {
        $socialLink->status = $status;
        $socialLink->saveOrFail();
    }

    public function create(array $data): WebSocialLink
    {
        return WebSocialLink::create($data);
    }
}
