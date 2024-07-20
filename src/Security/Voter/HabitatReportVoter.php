<?php

namespace App\Security\Voter;

use App\Entity\HabitatReport;
use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Security\Core\User\UserInterface;

class HabitatReportVoter extends Voter
{
    public const EDIT = 'edit';
    public const AJAX_DELETE = 'ajaxDelete';

    private Security $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    protected function supports(string $attribute, $subject): bool
    {
        return in_array($attribute, [self::EDIT, self::AJAX_DELETE])
            && $subject instanceof HabitatReport;
    }

    protected function voteOnAttribute(string $attribute, $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();

        if (!$user instanceof UserInterface) {
            return false;
        }

        /** @var HabitatReport $medicalReport */
        $medicalReport = $subject;

        switch ($attribute) {
            case self::EDIT:
            case self::AJAX_DELETE:
                return $this->canEditOrDelete($habitatReport, $user);
        }

        return false;
    }

    private function canEditOrDelete(HabitatReport $habitatReport, User $user): bool
    {
        return $medicalReport->getUser() === $user || $this->security->isGranted('ROLE_ADMIN');
    }
}


