<?php declare(strict_types = 1);

namespace MillmanPhotography\Controller;

use Exception;
use Slim\Http\Request;
use Slim\Http\Response;
use Projek\Slim\Monolog;
use Swift_Message as SwiftMessage;

use MillmanPhotography\Mailer;
use MillmanPhotography\Entity\Enquiry;
use MillmanPhotography\Resource\EnquiryResource;
use MillmanPhotography\Validator\EnquiryValidator;
use MillmanPhotography\Exception\MailerException;

class EnquiryController
{
    /** @var EnquiryValidator $validator */
    private $validator;

    /** @var EnquiryResource $resource */
    private $resource;

    /** @var Mailer $mailer */
    private $mailer;

    /** @var Monolog $logger */
    private $logger;

    /**
     * @param EnquiryValidator $validator
     * @param EnquiryResource $resource
     * @param Mailer $mailer
     * @param Monolog $logger
     */
    public function __construct(
        EnquiryValidator $validator,
        EnquiryResource $resource,
        Mailer $mailer,
        Monolog $logger
    ) {
        $this->validator = $validator;
        $this->resource = $resource;
        $this->mailer = $mailer;
        $this->logger = $logger;
    }

    /**
     * @param Request $request
     * @param Response $response
     * @return Response
     */
    public function __invoke(Request $request, Response $response) :Response
    {
        $data = $request->getParsedBody();

        if (!$this->validator->isValid($data)) {
            return $response->withJson($this->validator->getErrors(), 400);
        }

        try {
            $enquiry = $this->resource->create($data);

            $this->mailer->send('reply', ['enquiry' => $enquiry, 'title' => 'Enquiry Received'],
                function (SwiftMessage $message) use ($enquiry) {
                    $message->addPart($this->getReplyEmail($enquiry), 'text/plain');
                    $message->setFrom(getenv('SMTP_EMAIL'), 'Millman Photography')
                        ->setTo($enquiry->getEmail(), $enquiry->getName())
                        ->setSubject('Millman Photography Enquiry');
                }
            );

            $this->mailer->send('enquiry', ['enquiry' => $enquiry, 'title' => 'Enquiry Sent'],
                function (SwiftMessage $message) use ($enquiry) {
                    $message->addPart($this->getEnquiryEmail($enquiry), 'text/plain');
                    $message->setFrom($enquiry->getEmail(), $enquiry->getName())
                        ->setTo(getenv('SMTP_EMAIL'), 'Millman Photography')
                        ->setSubject('Millman Photography Enquiry');
                }
            );

            return $response->withJson(['Success'], 200);
        } catch (MailerException $exception) {
            $this->logger->log(100, $exception->getMessage() . ' in ' . $exception->getFile() . $exception->getLine());
            return $response->withJson(['Success: No Email Sent'], 200);
        } catch (Exception $exception) {
            $this->logger->log(100, $exception->getMessage() . ' in ' . $exception->getFile() . $exception->getLine());
            return $response->withJson(['Error: Try Again'], 404);
        }
    }

    /**
     * @param Enquiry $enquiry
     * @return string
     */
    private function getEnquiryEmail(Enquiry $enquiry) :string
    {
        return <<<EOT
You have received an Enquiry.

{$enquiry->getName()} sent you an Enquiry at {$enquiry->getDateCreated()->format('jS \of F Y')}

{$enquiry->getMessage()}

Reply to {$enquiry->getName()} at {$enquiry->getEmail()}
EOT;
    }

    /**
     * @param Enquiry $enquiry
     * @return string
     */
    private function getReplyEmail(Enquiry $enquiry) :string
    {
        return <<<EOT
Thank you for your Enquiry.

You sent the following Enquiry on the {$enquiry->getDateCreated()->format('jS \of F Y')}

{$enquiry->getMessage()}

I will be sure to get back to you as soon as possible.

Kind regards,
Freddie John Millman
EOT;
    }
}
