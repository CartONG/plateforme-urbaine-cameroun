<?php

namespace App\Services\State\Processor\Comments;

use App\Entity\AppContentComment;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

class BulkUpdateCommentDTO
{
    #[Assert\NotBlank]
    #[Assert\Type(type: 'array')]
    #[Groups([AppContentComment::COMMENT_BULK_UPDATE])]
    public array $commentIds;

    #[Assert\NotNull]
    #[Assert\Type(type: 'bool')]
    #[Groups([AppContentComment::COMMENT_BULK_UPDATE])]
    public bool $readByAdmin;
}
