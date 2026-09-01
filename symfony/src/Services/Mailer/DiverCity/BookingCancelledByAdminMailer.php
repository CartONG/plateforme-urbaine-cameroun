<?php

namespace App\Services\Mailer\DiverCity;

use App\Entity\DiverCity\Booking;
use App\Services\Mailer\User\AbstractUserMailer;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mime\Address;

final class BookingCancelledByAdminMailer extends AbstractUserMailer
{
    public function send(Booking $booking): void
    {
        $email = (new TemplatedEmail())
            ->to(new Address($booking->getEmail(), $booking->getFirstName().' '.$booking->getLastName()))
            ->subject($this->translator->trans('mail.divercity.booking_cancelled_by_admin.subject'))
            ->htmlTemplate('mail/divercity/booking_cancelled_by_admin.html.twig')
            ->context(['booking' => $booking]);

        $this->mailer->send($email);
    }
}