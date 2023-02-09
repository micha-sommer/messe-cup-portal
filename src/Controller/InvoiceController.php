<?php

namespace App\Controller;

use App\Entity\Registration;
use DateTime;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;
use TCPDF;

#[Route('/{_locale<%app.supported_locales%>}/invoice')]
class InvoiceController extends AbstractController
{
    #[Route('/{id}', name: 'invoice_show', methods: "GET")]
    public function show(Registration $registration): Response
    {
        if ($this->getUser() !== $registration) {
            $this->denyAccessUnlessGranted("ROLE_ADMIN");
        }

        $pdf = $this->getInvoicePDF($registration);

        $pdf->Output('Rechnung.pdf');

        return new Response(200);
    }

    private function getInvoicePDF(Registration $registration): TCPDF
    {
        $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'utf-8', false);
        $pdf->setCreator(PDF_CREATOR);
        $pdf->setAuthor('Erfurter Judo-Club e.V.');
        $pdf->setTitle('Rechnung Messe-Cup/EGA-Pokal');
        /** @noinspection NullPointerExceptionInspection */
        $pdf->setSubject('Rechnung für ' . $registration->getClub());

        // remove default header/footer
        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);

        // Auswahl des Font
        $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
        // Auswahl der MArgins
        $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
        // Automatisches Autobreak der Seiten
        $pdf->SetAutoPageBreak(true, PDF_MARGIN_BOTTOM);
        // Image Scale
        $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
        // Schriftart
        $pdf->SetFont('dejavusans', '', 10);
        // Neue Seite
        $pdf->AddPage();

        $html = $this->render('invoice/pdf.html.twig', ['registration' => $registration])->getContent();
        $pdf->writeHTML($html, true, false, true);
        return $pdf;
    }

    #[Route('/{id}/mail', name: 'invoice_send_mail')]
    public function sendInvoicePerMail(
        Request             $request,
        Registration        $registration,
        MailerInterface     $mailer,
        TranslatorInterface $translator,
    ): Response
    {
        $timestamp = new DateTime();
        $name = $translator->trans('welcome.contact-us.name');
        $subject = $translator->trans('invoice.mail.subject', [
            'club' => $registration->getClub(),
        ]);
        $title = $translator->trans('invoice.mail.title', [
            'name' => $registration->getFirstName() . ' ' . $registration->getLastName()
        ]);
        $greeting = $translator->trans('invoice.mail.greeting', [
            'timestamp' => $timestamp,
        ]);

        $pdf = $this->getInvoicePDF($registration);
        $data = $pdf->Output('Invoice_' . $registration->getClub() . '.pdf', 'S');
        $mail = (new TemplatedEmail())
            ->from(new Address('info@erfurter-judo-club.de', $name))
            ->to($registration->getEmail())
            ->subject($subject)
            ->context([
                'title' => $title,
                'greeting' => $greeting,
                'registration' => $registration,
                'requestLocale' => $request->getLocale(),
            ])
            ->htmlTemplate('invoice/email.html.twig')
            ->attach($data, 'Invoice_' . $registration->getClub() . '.pdf', 'application/pdf');

        $mailer->send($mail);

        return $this->redirectToRoute('welcome');
    }
}
