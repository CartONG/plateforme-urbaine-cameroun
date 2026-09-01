<?php

namespace App\Services\Mailer\DiverCity;

use App\Entity\DiverCity\Booking;
use App\Services\Mailer\User\AbstractUserMailer;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mime\Address;

final class BookingAcceptedMailer extends AbstractUserMailer
{
    public function send(Booking $booking): void
    {
        $email = (new TemplatedEmail())
            ->to(new Address($booking->getEmail(), $booking->getFirstName().' '.$booking->getLastName()))
            ->subject($this->translator->trans('mail.divercity.booking_accepted.subject'))
            ->htmlTemplate('mail/divercity/booking_accepted.html.twig')
            ->context(['booking' => $booking]);

        $this->mailer->send($email);
    }
}