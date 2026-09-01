<?php

namespace App\Services\Mailer\DiverCity;

use App\Entity\DiverCity\Booking;
use App\Entity\User\User;
use App\Services\Mailer\User\AbstractUserMailer;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mime\Address;

final class BookingCancelledByUserMailer extends AbstractUserMailer
{
    /**
     * @param User[] $admins
     */
    public function send(Booking $booking, array $admins): void
    {
        foreach ($admins as $admin) {
            $email = (new TemplatedEmail())
                ->to(new Address($admin->getEmail(), $admin->getFullName()))
                ->subject($this->translator->trans('mail.divercity.booking_cancelled_by_user.subject'))
                ->htmlTemplate('mail/divercity/booking_cancelled_by_user.html.twig')
                ->context(['booking' => $booking]);

            $this->mailer->send($email);
        }
    }
}
