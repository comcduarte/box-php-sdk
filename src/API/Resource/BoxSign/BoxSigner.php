<?php
declare(strict_types = 1);
namespace comcduarte\Box\API\Resource\BoxSign;

class BoxSigner
{

    public string $declined_redirect_url;

    public string $email;

    public string $embed_url;

    public string $embed_url_external_user_id;

    public bool $has_viewed_document;

    public string $iframeable_embed_url;

    public array $inputs = [];

    public bool $is_in_person;

    public bool $login_required;

    public int $order;

    public string $password;

    public string $redirect_url;

    public string $role;

    public BoxSignerDecision $signer_decision;

    public string $signer_group_id;

    public bool $suppress_notifications;

    public string $verification_phone_number;
}